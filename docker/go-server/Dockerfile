# Используем официальный образ Go
FROM golang:1.20-alpine

# Устанавливаем необходимые инструменты
RUN apk add --no-cache git
# Устанавливаем рабочую директорию
WORKDIR /app

# Копируем исходный код
COPY websocket-server.go .

# Устанавливаем зависимостиd
RUN go mod init websocket-server && go mod tidy

# Собираем приложение
RUN go build -o websocket-server

# Проверяем содержимое директории
RUN ls -la

# Открываем порт сервера
EXPOSE 8082

# Запускаем сервер
CMD ["websocket-server"]
