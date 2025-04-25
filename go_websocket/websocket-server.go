package main

import (
	"encoding/json"
	"fmt"
	"log"
	"net/http"
)

// структура для парсинга входящего JSON
type Message struct {
	Text string `json:"text"`
}

// обработчик GET-запроса
func handleGet(w http.ResponseWriter, r *http.Request) {
	response := map[string]string{"message": "Hello from Go (GET)"}
	json.NewEncoder(w).Encode(response)
}

// обработчик POST-запроса
func handlePost(w http.ResponseWriter, r *http.Request) {
	var msg Message

	// читаем тело запроса и парсим JSON
	if err := json.NewDecoder(r.Body).Decode(&msg); err != nil {
		http.Error(w, "Invalid JSON", http.StatusBadRequest)
		return
	}

	fmt.Printf("🟢 Received POST message: %s\n", msg.Text)

	response := map[string]string{"received": msg.Text}
	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(response)
}

func main() {
	http.HandleFunc("/get", handleGet)
	http.HandleFunc("/post", handlePost)

	port := ":8082"
	fmt.Println("🚀 Go HTTP server started on http://localhost" + port)
	log.Fatal(http.ListenAndServe(port, nil))
}
