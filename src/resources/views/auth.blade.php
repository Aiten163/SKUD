<div class="p-5">
        <!-- Поле ввода времени -->
        <input type="number" id="time-input" placeholder="Время (в секундах)" class="">

        <!-- Кнопка генерации -->
        <button id="generate-code" class="btn btn-primary mt-3">Сгенерировать код</button>

        <!-- Отображение кода -->
        <div id="code-display" class="mt-4 text-lg font-bold h2 "></div>

        <!-- Таймер -->
        <div id="timer-display" class="mt-2 text-danger"></div>
</div>

<!-- Подключаем JavaScript файл -->
@vite(['resources/js/two-factor-auth.js'])
