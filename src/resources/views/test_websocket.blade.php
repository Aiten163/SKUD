<div class="container mt-4">
    <h1 class="mb-4">WebSocket Laravel-Go Test</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Отправка события</h3>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="event" placeholder="Имя события" value="test">
                    </div>
                    <div class="mb-3">
                        <label for="data" class="form-label">Данные события (JSON):</label>
                        <textarea class="form-control" id="data" rows="5">{
    "field1": "value1",
    "field2": "value2"
}</textarea>
                    </div>
                    <div class="mb-3">
                        Вернуть ли ответ? (return_answer)
                        <input id="return_answer" class="form-check-input" checked type="checkbox">
                    </div>
                    <button id="sendEventButton" class="btn btn-primary" onclick="sendEvent()">Отправить событие</button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Лог сообщений</h3>
                    <div class="log-area" id="messageLog"></div>
                    <h4 class="mt-3">Статус соединения</h4>
                    <div id="connectionStatus" class="alert alert-info">Устанавливаем соединение...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<h2>Инструкция </h2>
<h4>Формат отправленных данных:</h4>
<ul class="list-group">
    <li class="list-group-item">event: - string</li>
    <li class="list-group-item">data: - array</li>
    <li class="list-group-item">return_answer: - bool</li>
</ul>
<b>return_answer</b>  - True/False вернуть ли ответ, в основном для дебага

<script>
    const socketUrl = "ws://localhost:8082/ws";
    let socket;

    const statusElement = document.getElementById("connectionStatus");
    const logElement = document.getElementById("messageLog");

    function logMessage(type, message) {
        const div = document.createElement("div");
        div.className = `alert alert-${type === 'sent' ? 'primary' : 'success'} mt-2`;
        div.innerText = `${type === 'sent' ? 'Отправлено' : 'Получено'}: ${message}`;
        logElement.prepend(div); // Последние сообщения сверху
    }

    function connectWebSocket() {
        socket = new WebSocket(socketUrl);

        socket.onopen = () => {
            statusElement.className = "alert alert-success";
            statusElement.textContent = "Соединение установлено";
        };

        socket.onmessage = (event) => {
            logMessage('received', event.data);
        };

        socket.onclose = () => {
            statusElement.className = "alert alert-warning";
            statusElement.textContent = "Соединение закрыто. Повторная попытка через 3 секунды...";
            setTimeout(connectWebSocket, 3000);
        };

        socket.onerror = (error) => {
            console.error("WebSocket Error:", error);
        };
    }

    function sendEvent() {
        const event = document.getElementById("event").value;
        const dataText = document.getElementById("data").value;
        const return_answer = document.getElementById('return_answer').value === 'on' ? 'True' : 'False';


        let dataObj;
        try {
            dataObj = JSON.parse(dataText);
        } catch (e) {
            alert("Неверный JSON формат в данных события!(возможно лишняя запятая)");
            return;
        }

        const payload = JSON.stringify({
            event: event,
            data: dataObj,
            return_answer: return_answer
        });

        if (socket.readyState === WebSocket.OPEN) {
            console.log(payload);
            socket.send(payload);
            logMessage('sent', payload);
        } else {
            alert("Соединение с сервером не установлено.");
        }
    }

    window.addEventListener("load", connectWebSocket);
</script>
