package main

import (
	"context"
	"encoding/json"
	"log"
	"net/http"
	"sync"

	"github.com/go-redis/redis/v8"
	"github.com/gorilla/websocket"
)

var (
	// Redis клиент
	redisClient *redis.Client

	// WebSocket клиенты
	clients   = make(map[*websocket.Conn]bool)
	clientsMu sync.Mutex

	// WebSocket апгрейдер
	upgrader = websocket.Upgrader{
		CheckOrigin: func(r *http.Request) bool { return true },
	}
)

// Сообщение для передачи между клиентами и Laravel
type Message struct {
	Event string      `json:"event"`
	Data  interface{} `json:"data"`
}

func main() {
	// Инициализация Redis
	initRedis()
	defer redisClient.Close()

	// Запуск подписки на Redis в фоне
	go subscribeToRedis()

	// HTTP маршруты
	http.HandleFunc("/ws", handleWebSocket)

	log.Println("Сервер запущен на :8082")
	log.Fatal(http.ListenAndServe(":8082", nil))
}

// Инициализация Redis
func initRedis() {
	redisClient = redis.NewClient(&redis.Options{
		Addr:     "redis:6379", // Адрес Redis (должен совпадать с Laravel)
		Password: "",           // Пароль, если есть
		DB:       0,            // База данных
	})

	// Проверка подключения
	if _, err := redisClient.Ping(context.Background()).Result(); err != nil {
		log.Fatalf("Ошибка подключения к Redis: %v", err)
	}
}

// Обработчик WebSocket
func handleWebSocket(w http.ResponseWriter, r *http.Request) {
	conn, err := upgrader.Upgrade(w, r, nil)
	if err != nil {
		log.Printf("Ошибка WebSocket: %v", err)
		return
	}
	defer conn.Close()

	// Регистрация клиента
	registerClient(conn)
	defer unregisterClient(conn)

	log.Println("Новый клиент подключен")

	// Чтение сообщений от клиента
	for {
		var msg Message
		if err := conn.ReadJSON(&msg); err != nil {
			log.Printf("Ошибка чтения: %v", err)
			break
		}

		log.Printf("Получено от клиента: %+v", msg)

		// Отправка сообщения в Redis (для Laravel)
		if err := publishToRedis("from_go", msg); err != nil {
			log.Printf("Ошибка публикации в Redis: %v", err)
		}
	}
}

// Подписка на Redis (для получения сообщений от Laravel)
func subscribeToRedis() {
	ctx := context.Background()
	pubsub := redisClient.Subscribe(ctx, "from_laravel")
	defer pubsub.Close()

	ch := pubsub.Channel()

	for msg := range ch {
		var message Message
		if err := json.Unmarshal([]byte(msg.Payload), &message); err != nil {
			log.Printf("Ошибка декодирования: %v", err)
			continue
		}

		log.Printf("Получено от Laravel: %+v", message)

	}
}

// Публикация в Redis
func publishToRedis(channel string, msg Message) error {
	jsonMsg, err := json.Marshal(msg)
	if err != nil {
		return err
	}
	return redisClient.Publish(context.Background(), channel, jsonMsg).Err()
}

// Рассылка сообщений всем клиентам
func broadcastToClients(msg Message) {
	clientsMu.Lock()
	defer clientsMu.Unlock()

	for client := range clients {
		if err := client.WriteJSON(msg); err != nil {
			log.Printf("Ошибка отправки: %v", err)
			client.Close()
			delete(clients, client)
		}
	}
}

// Регистрация клиента
func registerClient(conn *websocket.Conn) {
	clientsMu.Lock()
	clients[conn] = true
	clientsMu.Unlock()
}

// Удаление клиента
func unregisterClient(conn *websocket.Conn) {
	clientsMu.Lock()
	delete(clients, conn)
	clientsMu.Unlock()
}
