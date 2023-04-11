<? include_once('includes/functions.php'); ?>
<html>
<head> 
  <meta charset="utf-8" content="text/html" />
  <title> Simple Stat </title>
  <link rel="stylesheet" type="text/css" href="/styles.css" />
<style>
.ac-container{
    width: 100%;
    margin: 10px auto 30px auto;
    font-size: 12px;
}

.ac-container label{
    padding: 5px 20px;
    position: relative;
    z-index: 20;
    display: block;
    height: 30px;
    cursor: pointer;
    color: #363636;
    text-shadow: 1px 1px 1px rgba(255,255,255,0.8);
    line-height: 33px;
    font-size: 14px;
    background: -moz-linear-gradient(top, #ffffff 1%, #eaeaea 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(1%,#ffffff), color-stop(100%,#eaeaea));
    background: -webkit-linear-gradient(top, #ffffff 1%,#eaeaea 100%);
    background: -o-linear-gradient(top, #ffffff 1%,#eaeaea 100%);
    background: -ms-linear-gradient(top, #ffffff 1%,#eaeaea 100%);
    background: linear-gradient(top, #ffffff 1%,#eaeaea 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eaeaea',GradientType=0 );
    box-shadow: 
        0px 0px 0px 1px rgba(155,155,155,0.3), 
        1px 0px 0px 0px rgba(255,255,255,0.9) inset, 
        0px 2px 2px rgba(54,54,54,0.1);
}
}

.ac-container label:hover{
    background: #fff;
}

.ac-container input:checked + label,
.ac-container input:checked + label:hover{
    background: #bababa;
    text-shadow: 0px 1px 1px rgba(255,255,255, 0.6);
    box-shadow: 
        0px 0px 0px 1px rgba(54,54,54,0.3), 
        0px 2px 2px rgba(54,54,54,0.1);
}

.ac-container label:hover:after,
.ac-container input:checked + label:hover:after{
    content: '>>';
    position: absolute;
    width: 24px;
    height: 24px;
    right: 13px;
    top: 7px;
}

.ac-container input{
    display: none;
}

.ac-container article{
    margin-top: -1px;
    overflow: hidden;
    height: 0px;
    position: relative;
    z-index: 10;
    -webkit-transition: height 0.3s ease-in-out, box-shadow 0.6s linear;
    -moz-transition: height 0.3s ease-in-out, box-shadow 0.6s linear;
    -o-transition: height 0.3s ease-in-out, box-shadow 0.6s linear;
    -ms-transition: height 0.3s ease-in-out, box-shadow 0.6s linear;
    transition: height 0.3s ease-in-out, box-shadow 0.6s linear;
}
.ac-container input:checked ~ article {
     -webkit-transition: height 0.3s ease-in-out, box-shadow 0.6s linear;
    -moz-transition: height 0.3s ease-in-out, box-shadow 0.6s linear;
    -o-transition: height 0.3s ease-in-out, box-shadow 0.6s linear;
    -ms-transition: height 0.3s ease-in-out, box-shadow 0.6s linear;
    transition: height 0.3s ease-in-out, box-shadow 0.6s linear;
    height: 400px;
}

.ac-container article p{
    color: #363636;
    line-height: 23px;
    font-size: 15px;
    padding: 10px;
    text-shadow: 1px 1px 1px rgba(255,255,255,0.8);
}

#footer {background-color: #808080;
    position: fixed; /* Фиксированное положение */
    left: 0; bottom: 0; /* Левый нижний угол */
    padding: 10px; /* Поля вокруг текста */
    color: #fff; /* Цвет текста */
    width: 100%; /* Ширина слоя */
    z-index: 9999;
     }
</style>
</head>

<body>
  <? include_once('includes/header.php'); ?>
  <? include_once('includes/menu.php'); ?>
  <div class="center">
  
  <h3>Справка</h3>

<section class="ac-container">
    <div>
        <input id="ac-1" name="accordion-1" type="radio" checked />
        <label for="ac-1">Общие сведения</label>
        <article>
            <p>Данное приложение предназначено для получения, обработки, хранения и визуализации данных статистики о затратах и эффективности рекламных каналов. <br> 
              Данные статистики поступают из Аналитических систем (<a href="https://metrika.yandex.ru">Яндекс Метрика</a>), и рекламных кабинетов (<a href="https://direct.yandex.ru">Яндекс Директ</a>, <a href="https://ads.google.com">Google Adwords</a>, <a href="https://vk.com/ads">VK</a>, и других рекламных систем с поддержкой API)</p>
            <p>This application is designed to receive, process, store and visualize statistical data on the costs and effectiveness of advertising channels.
              Statistics data comes from Analytical systems (Yandex Metrika), and advertising offices (Yandex Direct, Google Adwords, VK, and other advertising systems with API support)</p>
        </article>
    </div>
    <div>
        <input id="ac-2" name="accordion-1" type="radio" />
        <label for="ac-2">Получение данных</label>
        <article>
            <p>Для получения данных статистики, необходимо перейти на страницу <a href="index.php">Получение статистики</a><br>
              Затем в поле "Выберите период" необходимо указать даты*, за которые необходимо получить данные. <img src="1.png"><br>
            *Важно! Максимальная дата в поле "По:" может быть не позднее, чем дата, предшествующая сегодняшней. При этом, если в этом поле будет введена дата "сегодня", или позже, то програмной ошибки не случится, а данные будут получены до "вчерашней" даты.<br>
            Для вызова процедуры получения данных, нажать <br><img src="2.png"></p>
        </article>
    </div>
    <div>
        <input id="ac-3" name="accordion-1" type="radio" />
        <label for="ac-3">Метод визуализации данных</label>
        <article>
            <p>Полученные данные преобразуются в древовидную таблицу на уровни: <br>
         Рекламный источник (Yandex - Я.Директ, Google - G.Adwords, и др.)<br>
         Тип площадки (Поисковый трафик или Сетевой)<br>
         Название рекламной кампании<br>
         Название группы объявлений<br>
         Поисковая фраза / Количество переходов за выбранный период (Клики)/ Колчество Заявок / Сумма затраченных средств (Стоимость) / Расчетная средняя стоимость заявки за выбранный период (Цена заявки)<br>
         <img src="3.png"></p>
        </article>
    </div>
    <div>
        <input id="ac-4" name="accordion-1" type="radio" />
        <label for="ac-4">Контакты</label>
        <article>
            <p>marketing@yagoda-group.ru<br></p>
        </article>
    </div>
</section>
<br><br><br>
 <h3> Политика конфиденциальности </h3>
   <p>
Компания ООО «Ягода-групп» очень ответственно подходит к вопросам конфиденциальности ваших данных.<br><br>

Как компания ООО «Ягода-групп» работает с личными данными.<br><br>

В ООО «Ягода-групп» очень ответственно подходят к сохранению конфиденциальности ваших данных. Политика конфиденциальности содержит разъяснения, касающиеся того, каким образом мы будем собирать и использовать информацию, которую вы нам предоставите. Мы можем периодически вносить изменения в данный документ; текущей версией является та, которая опубликована на этом сайте. Местоимение «мы» относится к компании ООО «Ягода-групп», если не указано иное.<br><br>

1. Сбор ваших данных
Время от времени вы можете предоставлять свои личные данные (например, имя, адрес электронной почты и т. д.) для того, чтобы пользоваться услугами на нашем сайте. При сборе такой информации мы всегда будем использовать только безопасное соединение.<br>

Мы будем действовать в соответствии с применимым законодательством и будем предоставлять ссылку на Уведомление о защите данных каждый раз, когда мы будем запрашивать ваши личные данные, а также на форму Согласия об обработке персональных данных, когда, согласно законодательству, мы обязаны взять такое Согласие. Обрабатывать ваши личные данные мы будем только на основании вашего Согласия.<br>

Уведомление об ограниченной защите данных от компании ООО «Ягода-групп».<br>

Компания ООО «Ягода-групп» может использовать предоставленные вами данные в административных и маркетинговых целях, в интересах службы по работе с клиентами и для анализа ваших личных предпочтений. Мы можем хранить ваши данные в течение разумного периода времени для того, чтобы связываться с вами по поводу наших услуг. Информация о том, как мы используем файлы cookie и другие способы отслеживания в Интернете, содержится в разделе 2 настоящей Политики конфиденциальности.<br>

В случае размещения заявки на тест-драйв ваши данные могут быть переданы соответствующему дилеру (наименование и адрес дилера можно узнать на данном сайте), который может напрямую связаться с вами.<br>

Вы имеете право запросить копию ваших данных и исправить любые неточности. На нашем сайте вы можете узнать, как это сделать.<br><br>

2. Как мы собираем данные о ваших посещениях нашего сайта
Когда вы посещаете страницы нашего сайта, мы загружаем на ваш компьютер так называемые «файлы cookie» (см. раздел 6). Мы также можем собирать данные, используя веб-маяки (также известные как «пустые GIF» или «веб-жучки» — см. раздел 7). Файлы cookie и веб-маяки обеспечивают нас неперсонифицированными статистическими данными о том, что посетители делают на нашем сайте. Например: время, в течение которого была просмотрена страница; обычные маршруты перемещения по сайту; данные о настройках экрана и другая информация общего характера. Мы можем также собирать данные о том, какие еще сайты вы посещаете после нашего. Мы используем полученные данные для того, чтобы повышать качество услуг на нашем сайте и делать его более полезным для вас.<br><br>

3. Что мы будем делать с вашими личными данными
Мы будем использовать ваши данные только в тех целях, которые, в соответствии с разумными ожиданиями, должны представляться вам очевидными или которые будут объявлены при сборе информации. В случаях, когда будет необходимо ваше согласие, мы будем использовать ваши данные только после того, как вы его дадите. Мы не будем связываться с вами и передавать ваши данные нашим коммерческим партнерам в маркетинговых целях, если вы не согласитесь с такими действиями.<br>

Мы обязуемся относиться к вашим личным данным с должным вниманием и в соответствии с принципами защиты данных. Мы гарантируем защиту ваших персональных данных, в том числе с помощью соответствующих технических средств защиты.<br><br>

4. Как получить доступ к своим личным данным
Вы можете направлять запросы менеджеру по защите и соответствию данных по адресу marketing@yagoda-group.ru. Если вы считаете, что какая-либо информация о вас недостоверна или используется ненадлежащим образом, или если вы хотите получить более подробную информацию, свяжитесь с нами по вышеупомянутому адресу.<br><br>

5. Пользователи в возрасте до 18 лет включительно
Если вам 18 лет или менее, вы должны получить разрешение родителя или опекуна, перед тем как передать нам личные данные. Если мы выясним, что получили информацию от вас без надлежащего разрешения, мы оставляем за собой право отменить все транзакции и услуги, а также удалить все предоставленные вами личные данные. Вы сможете заново направить нам информацию после того, как получите необходимое разрешение.<br><br>

6. Что такое cookie?
Cookie — это текстовый файл, который позволяет нашему серверу идентифицировать ваш компьютер (посредством IP-адреса). Информация о том, для чего мы это делаем, содержится в разделе 2. Хотя в некоторых случаях это представляется возможным, мы не используем IP-адреса для идентификации пользователей. Как правило, у вас есть возможность настроить свой компьютер так, чтобы либо принимать все файлы cookie, либо получать уведомления при их передаче, либо никогда не принимать такие файлы. Задать эти настройки можно непосредственно в браузере. В большинстве браузеров такая возможность доступна через меню (например, пункт «Сервис» в Internet Explorer или пункт «Инструменты» в Mozilla Firefox). Если у вас возникнут трудности с доступом к этим настройкам, обратитесь к справочному разделу в вашем браузере. Вы также можете найти более подробную информацию о файлах cookie и управлении ими на сайте www.allaboutcookies.org.<br>

Отказ от приема cookie может сделать невозможной нормальную работу сайта или даже лишить вас доступа к определенным разделам.<br><br>

7. Что такое веб-маяк?
Некоторые наши веб-страницы могут содержать электронные изображения, так называемые веб-маяки (также известны как «пустые GIF»), которые позволяют нам посчитать число пользователей, посетивших данные страницы. Веб-маяки собирают только ограниченные данные, включающие номер cookie, время и дату просмотра страницы, а также описание страницы, на которой расположен маяк. Предоставляемые такими маяками данные не могут быть персонально идентифицированы, они используются только для отслеживания эффективности нашего сайта в целом или отдельных кампаний.<br>

Дополнительная информация о веб-маяках, их использовании и ваших правах доступна на сайте www.allaboutcookies.org/web-beacons/

По любым вопросам, связанным с данной Политикой конфиденциальности или другими аспектами процессов хранения и обработки данных, обращайтесь на электронный адрес, указанный ниже, с пометкой «Для менеджера по защите и соответствию данных».<br>

8. Ссылки на другие сайты
Наши веб-сайты могут содержать ссылки на сторонние сайты. Ни компания ООО «Ягода-групп», ни связанные с ней компании не несут ответственности за соблюдение конфиденциальности на таких сайтах и их безопасность. Политика конфиденциальности распространяется только на этот веб-сайт.</p>
<br>
  </div>
<? include_once('includes/footer.php'); ?>
</body>

</html>