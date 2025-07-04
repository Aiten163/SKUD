# Указываем базовый образ
FROM ubuntu:20.04

# Обновляем пакеты и устанавливаем curl и tar
RUN apt-get update && apt-get install -y curl tar

# Создаем каталог /FRP
RUN mkdir -p /FRP

# Скачиваем и распаковываем FRP
RUN curl -sSL https://github.com/fatedier/frp/releases/download/v0.58.0/frp_0.58.0_linux_amd64.tar.gz | tar -zxvf - -C /FRP

# Рабочая директория
WORKDIR /FRP

# Остальная часть твоего Dockerfile
