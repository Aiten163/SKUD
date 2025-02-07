# Используем официальный образ Go
FROM golang:1.20-alpine

# Устанавливаем необходимые инструменты
RUN apk add --no-cache git

# Устанавливаем рабочую директорию
WORKDIR /app

# Копируем файлы проекта
COPY . .

# Устанавливаем зависимости
RUN go mod init websocket-server && go mod tidy

# Собираем приложение
RUN go build -o websocket-server

# Открываем порт сервера
EXPOSE 8082

# Запускаем сервер
CMD ["./websocket-server"]
