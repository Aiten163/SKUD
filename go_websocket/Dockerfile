FROM golang:1.21-alpine

WORKDIR /app

COPY . .

ENV GOPROXY=https://proxy.golang.org,direct
ENV GONOSUMDB=*
ENV GODEBUG=netdns=go

RUN [ ! -f go.mod ] && go mod init websocket || true

RUN go mod tidy

RUN go build -v -o server websocket-server.go

RUN ls -la /app

EXPOSE 8082

CMD ["./server"]
