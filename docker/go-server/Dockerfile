FROM golang:1.20-alpine

WORKDIR /app
COPY docker/go-server .

RUN go mod init websocket-server && \
    go mod tidy && \
    cd /app && \
    go build -o /usr/bin/websocket-server && \
    ls -la /usr/bin/websocket-server

EXPOSE 8082
CMD ["websocket-server"]
