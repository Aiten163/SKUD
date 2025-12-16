package main

import (
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"sync"
	"time"

	"github.com/gorilla/websocket"
	"github.com/rabbitmq/amqp091-go"
)

var upgrader = websocket.Upgrader{
	CheckOrigin: func(r *http.Request) bool {
		return true
	},
	ReadBufferSize:  1024,
	WriteBufferSize: 1024,
}

type Client struct {
	conn *websocket.Conn
	mu   sync.Mutex
}

type Message struct {
	Event string      `json:"event"`
	Data  interface{} `json:"data"`
}

type Server struct {
	clients   map[*Client]bool
	clientsMu sync.RWMutex

	amqpConn *amqp091.Connection
	amqpChan *amqp091.Channel
	queue    amqp091.Queue
}

func NewServer() *Server {
	rabbitURL := "amqp://guest:guest@rabbitmq:5672/"

	conn, err := amqp091.Dial(rabbitURL)
	if err != nil {
		log.Fatal("Ошибка подключения к RabbitMQ:", err)
	}

	ch, err := conn.Channel()
	if err != nil {
		log.Fatal("Ошибка открытия канала RabbitMQ:", err)
	}

	q, err := ch.QueueDeclare(
		"ws_messages",
		true,
		false,
		false,
		false,
		nil,
	)
	if err != nil {
		log.Fatal("Ошибка объявления очереди:", err)
	}

	log.Println("Подключение к RabbitMQ установлено")

	return &Server{
		clients:  make(map[*Client]bool),
		amqpConn: conn,
		amqpChan: ch,
		queue:    q,
	}
}

func (s *Server) publishToRabbitMQ(body []byte) error {
	return s.amqpChan.Publish(
		"",
		s.queue.Name,
		false,
		false,
		amqp091.Publishing{
			ContentType: "application/json",
			Body:        body,
			Timestamp:   time.Now(),
		},
	)
}

func (s *Server) handleWebSocket() http.HandlerFunc {
	return func(w http.ResponseWriter, r *http.Request) {
		log.Println("Новое WebSocket соединение")

		conn, err := upgrader.Upgrade(w, r, nil)
		if err != nil {
			log.Println("Ошибка апгрейда:", err)
			return
		}

		client := &Client{conn: conn}

		s.clientsMu.Lock()
		s.clients[client] = true
		clientCount := len(s.clients)
		s.clientsMu.Unlock()

		defer func() {
			s.clientsMu.Lock()
			delete(s.clients, client)
			s.clientsMu.Unlock()
			conn.Close()
			log.Println("WebSocket соединение закрыто")
		}()

		welcome := Message{
			Event: "connected",
			Data: map[string]interface{}{
				"clients":   clientCount,
				"timestamp": time.Now().Unix(),
			},
		}

		welcomeBytes, _ := json.Marshal(welcome)
		client.mu.Lock()
		conn.WriteMessage(websocket.TextMessage, welcomeBytes)
		client.mu.Unlock()

		for {
			msgType, msg, err := conn.ReadMessage()
			if err != nil {
				log.Println("Ошибка чтения:", err)
				return
			}

			if msgType != websocket.TextMessage {
				continue
			}

			log.Printf("Сообщение от клиента: %s", string(msg))

			// 👉 отправка в RabbitMQ
			if err := s.publishToRabbitMQ(msg); err != nil {
				log.Println("Ошибка RabbitMQ:", err)
			}

			// echo-ответ
			echo := Message{
				Event: "echo",
				Data: map[string]interface{}{
					"message":   string(msg),
					"timestamp": time.Now().Unix(),
				},
			}

			echoBytes, _ := json.Marshal(echo)
			client.mu.Lock()
			conn.WriteMessage(websocket.TextMessage, echoBytes)
			client.mu.Unlock()
		}
	}
}

func (s *Server) handleHealth() http.HandlerFunc {
	return func(w http.ResponseWriter, r *http.Request) {
		s.clientsMu.RLock()
		count := len(s.clients)
		s.clientsMu.RUnlock()

		resp := map[string]interface{}{
			"status":  "ok",
			"clients": count,
			"time":    time.Now().Unix(),
		}

		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(resp)
	}
}

func main() {
	server := NewServer()
	defer server.amqpChan.Close()
	defer server.amqpConn.Close()

	http.HandleFunc("/ws", server.handleWebSocket())
	http.HandleFunc("/health", server.handleHealth())
	http.HandleFunc("/", func(w http.ResponseWriter, r *http.Request) {
		fmt.Fprint(w, "WebSocket server is running")
	})

	port := "8082"
	log.Println("Сервер запущен на порту", port)

	log.Fatal(http.ListenAndServe(":"+port, nil))
}
