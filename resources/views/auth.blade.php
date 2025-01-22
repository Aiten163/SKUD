<div class="p-5">
    <!-- Контейнер для кода двухфакторной аутентификации -->
    <div id="two-factor-auth" class="text-center">
        <h2>Двухфакторная аутентификация</h2>

        <!-- Поле ввода времени -->
        <input type="number" id="time-input" placeholder="Время (в секундах)" class="form-control mt-3" value="60">

        <!-- Кнопка генерации -->
        <button id="generate-code" class="btn btn-primary mt-3">Сгенерировать код</button>

        <!-- Отображение кода -->
        <div id="code-display" class="mt-4 text-lg font-bold h2 "></div>

        <!-- Таймер -->
        <div id="timer-display" class="mt-2 text-danger"></div>
    </div>
</div>

<!-- Подключаем JavaScript файл -->
@vite(['resources/js/two-factor-auth.js'])
