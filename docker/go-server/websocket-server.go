package main

import (
	"context"
	"fmt"
	"log"
	"net/http"
	"os"
	"sync"

	"github.com/gorilla/websocket"
	"github.com/redis/go-redis/v9"
)

var (
	upgrader = websocket.Upgrader{
		CheckOrigin: func(r *http.Request) bool { return true },
	}

	ctx = context.Background()
)

const (
	fromGoChannel      = "from_go"      // Канал для отправки из Go в Laravel
	fromLaravelChannel = "to_go"        // Канал для получения из Laravel в Go
)

type Message struct {
	Event string      `json:"event"`
	Data  interface{} `json:"data"`
}

type Client struct {
	conn *websocket.Conn
	mu   sync.Mutex
}

func (c *Client) send(message []byte) error {
	c.mu.Lock()
	defer c.mu.Unlock()
	return c.conn.WriteMessage(websocket.TextMessage, message)
}

var clients = make(map[*Client]bool)
var clientsMu sync.Mutex

func addClient(c *Client) {
	clientsMu.Lock()
	defer clientsMu.Unlock()
	clients[c] = true
}

func removeClient(c *Client) {
	clientsMu.Lock()
	defer clientsMu.Unlock()
	delete(clients, c)
}

func broadcastToClients(message []byte) {
	clientsMu.Lock()
	defer clientsMu.Unlock()
	for client := range clients {
		if err := client.send(message); err != nil {
			log.Println("Ошибка при отправке клиенту:", err)
			client.conn.Close()
			delete(clients, client)
		}
	}
}

func publishToRedis(rdb *redis.Client, message []byte) {
	err := rdb.Publish(ctx, fromGoChannel, message).Err()
	if err != nil {
		log.Println("Ошибка при публикации в Redis:", err)
	}
}

func subscribeFromRedis(rdb *redis.Client) {
	pubsub := rdb.Subscribe(ctx, fromLaravelChannel)
	defer pubsub.Close()

	ch := pubsub.Channel()

	for msg := range ch {
		log.Println("Получено из Redis от Laravel:", msg.Payload)
		broadcastToClients([]byte(msg.Payload))
	}
}

func handleWebSocket(rdb *redis.Client) http.HandlerFunc {
	return func(w http.ResponseWriter, r *http.Request) {
		conn, err := upgrader.Upgrade(w, r, nil)
		if err != nil {
			log.Println("Ошибка при апгрейде:", err)
			return
		}
		defer conn.Close()

		client := &Client{conn: conn}
		addClient(client)
		defer removeClient(client)

		log.Println("Новый клиент подключён")

		for {
			_, message, err := conn.ReadMessage()
			if err != nil {
				log.Println("Ошибка при чтении:", err)
				break
			}

			log.Printf("От клиента: %s", message)

			// Публикуем сообщение в Redis
			publishToRedis(rdb, message)
		}
	}
}

func main() {
	redisHost := getEnv("REDIS_HOST", "redis")
	redisPort := getEnv("REDIS_PORT", "6379")

	rdb := redis.NewClient(&redis.Options{
		Addr:     fmt.Sprintf("%s:%s", redisHost, redisPort),
		Password: "",
		DB:       0,
	})

	if _, err := rdb.Ping(ctx).Result(); err != nil {
		log.Fatal("Redis недоступен:", err)
	}

	go subscribeFromRedis(rdb)

	http.HandleFunc("/ws", handleWebSocket(rdb))

	port := getEnv("PORT", "8082")
	log.Printf("Сервер запущен на :%s", port)
	log.Fatal(http.ListenAndServe(":"+port, nil))
}

func getEnv(key, fallback string) string {
	if value, exists := os.LookupEnv(key); exists {
		return value
	}
	return fallback
}