FROM golang:1.22-alpine

WORKDIR /app

# Копируем только необходимые файлы
COPY go.mod go.sum ./
COPY websocket-server.go ./

# Скачиваем зависимости
RUN go mod tidy

# Компилируем
RUN go build -o server websocket-server.go

EXPOSE 8082

CMD ["./server"]