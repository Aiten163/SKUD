FROM alpine:latest

# Устанавливаем зависимости
RUN apk update && apk add --no-cache curl

# Скачиваем FRP
RUN curl -sSL https://github.com/fatedier/frp/releases/download/v0.58.0/frp_0.58.0_linux_amd64.tar.gz | tar -zxvf - -C /FRP

# Рабочая директория
WORKDIR /FRP/frp_0.58.0_linux_amd64

# Запускаем frps
CMD ["./frps", "-c", "frps.ini"]
