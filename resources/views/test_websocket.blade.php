<h1>WebSocket Laravel-Go Test</h1>
<input type="text" id="messageInput" placeholder="Введите сообщение">
<button onclick="sendMessage()">Отправить</button>

<h2>Сообщения:</h2>
<ul id="messages"></ul>

<script>
    const socket = new WebSocket("ws://localhost:8082/ws"); // Go WebSocket сервер

    socket.onopen = () => {
        console.log("Соединение установлено с Go WebSocket сервером");
    };

    socket.onmessage = (event) => {
        const data = JSON.parse(event.data);
        const li = document.createElement("li");
        li.textContent = `От Laravel: ${JSON.stringify(data)}`;
        document.getElementById("messages").appendChild(li);
    };

    socket.onerror = (error) => {
        console.error("WebSocket ошибка:", error);
    };

    function sendMessage() {
        const input = document.getElementById("messageInput");
        const message = input.value.trim();
        if (!message) return;

        const payload = {
            event: "test_event",
            data: message
        };

        socket.send(JSON.stringify(payload));
        input.value = "";
    }
</script>
@vite(['resources/css/test_websocket.css'])
@vite(['resources/js/app.js'])
@vite(['resources/js/websocket.js'])
