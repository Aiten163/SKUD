package main

import (
	"context"
	"log"
	"net/http"
	"sync"
	"github.com/go-redis/redis/v8"
	"github.com/gorilla/websocket"
)

// Конфигурация Redis
var redisAddr = "localhost:6379"
var ctx = context.Background()

// Redis клиент
var redisClient *redis.Client

// Подключенные WebSocket-клиенты
var clients = make(map[*websocket.Conn]bool)
var clientsMutex = sync.Mutex{}

// WebSocket обновления
var upgrader = websocket.Upgrader{
	CheckOrigin: func(r *http.Request) bool {
		return true
	},
}

// Инициализация Redis
func initRedis() {
	redisClient = redis.NewClient(&redis.Options{
		Addr: redisAddr,
	})
	_, err := redisClient.Ping(ctx).Result()
	if err != nil {
		log.Fatalf("Ошибка подключения к Redis: %v", err)
	}
}

// WebSocket обработчик
func handleConnections(w http.ResponseWriter, r *http.Request) {
	ws, err := upgrader.Upgrade(w, r, nil)
	if err != nil {
		log.Printf("Ошибка при подключении WebSocket: %v", err)
		return
	}
	defer ws.Close()

	clientsMutex.Lock()
	clients[ws] = true
	clientsMutex.Unlock()

	log.Println("Клиент подключился")

	// Чтение сообщений от клиента
	for {
		var msg map[string]interface{}
		err := ws.ReadJSON(&msg)
		if err != nil {
			log.Printf("Ошибка чтения WebSocket: %v", err)
			clientsMutex.Lock()
			delete(clients, ws)
			clientsMutex.Unlock()
			break
		}

		// Отправляем сообщение в Redis для Laravel
		err = redisClient.Publish(ctx, "to-laravel-channel", msg).Err()
		if err != nil {
			log.Printf("Ошибка публикации в Redis: %v", err)
		}
	}
}

// Рассылка сообщений всем клиентам
func broadcastMessage(message string) {
	clientsMutex.Lock()
	defer clientsMutex.Unlock()

	for client := range clients {
		err := client.WriteJSON(map[string]string{"message": message})
		if err != nil {
			log.Printf("Ошибка отправки сообщения: %v", err)
			client.Close()
			delete(clients, client)
		}
	}
}

// Подписка на канал Redis
func subscribeToRedis() {
	subscriber := redisClient.Subscribe(ctx, "to-go-channel")

	// Обработка сообщений из Redis
	for {
		msg, err := subscriber.ReceiveMessage(ctx)
		if err != nil {
			log.Printf("Ошибка получения сообщения из Redis: %v", err)
			continue
		}

		log.Printf("Сообщение из Redis: %s", msg.Payload)
		broadcastMessage(msg.Payload)
	}
}

func main() {
	// Инициализация Redis
	initRedis()
	defer redisClient.Close()

	// Запуск подписки на Redis в отдельной горутине
	go subscribeToRedis()

	// Маршрут WebSocket
	http.HandleFunc("/ws", handleConnections)

	// Запуск HTTP-сервера
	log.Println("WebSocket сервер запущен на :8080")
	err := http.ListenAndServe(":8080", nil)
	if err != nil {
		log.Fatalf("Ошибка запуска сервера: %v", err)
	}
}
