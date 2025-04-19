document.addEventListener("DOMContentLoaded", function () {
    const generateButton = document.getElementById("generate-code");
    const codeDisplay = document.getElementById("code-display");
    const timerDisplay = document.getElementById("timer-display");
    const timeInput = document.getElementById("time-input");

    let timer = null;

    // Обработчик события клика по кнопке
    generateButton.addEventListener("click", () => {
        const timeInSeconds = parseInt(timeInput.value) || 60;

        // Отправляем запрос на сервер для генерации кода
        fetch("/api/generate-code", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ time: timeInSeconds }) // Передаем время на сервер
        })
            .then((response) => response.json())
            .then((data) => {
                console.log(data.message); // Логируем сообщение, что код сгенерирован
                if (data.code) { // Проверяем, есть ли код в ответе
                    codeDisplay.innerText = `Ваш код: ${data.code}`; // Отображаем код
                }
                startTimer(timeInSeconds); // Запускаем таймер с выбранным временем
            });
    });

    // Функция для запуска таймера
    function startTimer(timeInSeconds) {
        let timeLeft = timeInSeconds; // Таймер на выбранное время
        timerDisplay.innerText = `Оставшееся время: ${timeLeft} секунд`;

        // Запускаем отсчет времени
        clearInterval(timer); // Сбрасываем старый таймер
        timer = setInterval(() => {
            timeLeft -= 1;
            timerDisplay.innerText = `Оставшееся время: ${timeLeft} секунд`;

            if (timeLeft <= 0) {
                clearInterval(timer); // Останавливаем таймер
                codeDisplay.innerText = "Код больше недоступен.";
                timerDisplay.innerText = "";

                // Разблокируем кнопку
                generateButton.disabled = false;
                generateButton.innerText = "Сгенерировать код"; // Меняем текст кнопки обратно

                // Отправляем запрос на сервер для удаления кода
                fetch("/api/remove-code", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                    .then((response) => response.json())
                    .then((data) => {
                        console.log(data.message); // Логируем сообщение об удалении кода
                    });
            }
        }, 1000);

        // Отключаем кнопку
        generateButton.disabled = true;
        generateButton.innerText = "Генерация..."; // Меняем текст кнопки на "Генерация..."
    }
});
