# <b style=color:green> СКУД </b>

<h2 style=color:orange ><i>API</i></h2>
<h3> Главный (пока доработки) </h3>

http://skud.uuum19xb.beget.tech/api/door/{card_id}/{door_id}

<h3> Для теста </h3>

http://skud.uuum19xb.beget.tech/api/door/test/{action}/{lock_id}/{card_id}
  <p>{action} = lock | unlock</p>
<h3> Получить всю таблицу </h3>
    <p> http://skud.uuum19xb.beget.tech/api/door/getDoors </p> 
    <p> http://skud.uuum19xb.beget.tech/api/door/getCards </p> 
    <p> http://skud.uuum19xb.beget.tech/api/door/getLocks  </p> 
<h3> Связать дверь и замок </h3>
   <a> http://skud.uuum19xb.beget.tech/api/door/link/{lock_id}/{card_id} </a>


<h1>План</h1>
<h2>v0 (Готов сервак)</h2> 
<li>Настроить базу данных с фабрикамвфи</li>
<li>Сделать работающий API</li>
<h2>v1 (Основная часть сайта)</h2>
<li>Админку с круд
<ol>1) Двери</ol>
<ol>2) Карточки</ol>
<ol>3) Замки</ol>
</li>
<li>Страницы сайта</li>
<li>Логи входов</li>
<h2>v2 (Дополнения)</h2>
<li>Возможно допилить админку</li>
<li>Разместить сайт на постоянный хостинг</li>
<li>Обезопасить API</li>
