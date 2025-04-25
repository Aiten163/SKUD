FROM golang:1.22-alpine

WORKDIR /app

COPY . .

ENV GOPROXY=https://proxy.golang.org,direct
ENV GONOSUMDB=*
ENV GODEBUG=netdns=go

RUN [ ! -f go.mod ] && go mod init websocket || true
RUN go mod tidy
RUN go build -o server websocket-server.go

EXPOSE 8082

ENTRYPOINT ["./server"]
