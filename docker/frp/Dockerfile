FROM alpine:3.8

ARG frp_version=0.58.0

# Устанавливаем зависимости и FRP
RUN apk add --no-cache openssl && \
    mkdir -p /etc/frp && \
    cd /tmp && \
    wget -O frp.tar.gz "https://github.com/fatedier/frp/releases/download/v${frp_version}/frp_${frp_version}_linux_amd64.tar.gz" && \
    tar -xzf frp.tar.gz && \
    mv ./frp_${frp_version}_linux_amd64/frpc /usr/local/bin && \
    mv ./frp_${frp_version}_linux_amd64/frps /usr/local/bin && \
    rm -rf /tmp/*

WORKDIR /etc/frp

# Запускаем frpc в foreground режиме
CMD ["frpc", "-c", "/etc/frp/frpc.ini"]