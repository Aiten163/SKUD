<h1>WebSocket Laravel-Go Test</h1>
<input type="text" id="messageInput" placeholder="Введите сообщение">
<button id="sendButton" onclick="sendMessage()">Отправить</button>

<h2>Лог сообщений:</h2>
<ul id="messages"></ul>

<div id="debug" style="color: red; margin-top: 20px;"></div>

<script>
    const socket = new WebSocket("ws://localhost:8082/ws");
    const debug = document.getElementById('debug');
    const sendButton = document.getElementById('sendButton');

    // Функция для добавления сообщения в лог
    function addMessage(text, isOutgoing = false) {
        const li = document.createElement("li");
        li.textContent = text;
        li.style.color = isOutgoing ? 'blue' : 'green';
        document.getElementById("messages").appendChild(li);
    }

    // Обработчики WebSocket
    socket.onopen = () => {
        debug.textContent = "Соединение с WebSocket сервером установлено";
        addMessage("Подключено к серверу WebSocket");
        sendButton.disabled = false;
    };

    socket.onmessage = (event) => {
        debug.textContent = "Получено сообщение от сервера";
        try {
            const data = JSON.parse(event.data);
            addMessage(`Сервер: ${data.data || JSON.stringify(data)}`);
        } catch (e) {
            addMessage(`Сервер (не JSON): ${event.data}`);
        }
    };

    socket.onclose = () => {
        debug.textContent = "Соединение закрыто";
        addMessage("Отключено от сервера");
        sendButton.disabled = true;
    };

    socket.onerror = (error) => {
        debug.textContent = `Ошибка: ${error.message || 'Неизвестная ошибка'}`;
        sendButton.disabled = true;
    };

    // Функция отправки сообщения
    function sendMessage() {
        const input = document.getElementById("messageInput");
        const message = input.value.trim();
        if (!message) return;

        const payload = {
            event: "test_event",
            data: message
        };

        // Блокируем кнопку на время отправки
        sendButton.disabled = true;

        try {
            // Показываем отправленное сообщение
            addMessage(`Вы: ${message}`, true);

            socket.send(JSON.stringify(payload));
            input.value = "";
        } catch (e) {
            debug.textContent = `Ошибка отправки: ${e.message}`;
        } finally {
            // Разблокируем кнопку после отправки
            sendButton.disabled = false;
        }
    }

    // Отправка сообщения по нажатию Enter
    document.getElementById("messageInput").addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    // Изначально блокируем кнопку до установки соединения
    sendButton.disabled = true;
</script>
