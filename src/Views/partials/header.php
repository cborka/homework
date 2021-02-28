<header>
    <span id="show_error"></span>
    <br>header<br>
    <?php
        if(isset($_SESSION['login'])) {
            echo "Привет, {$_SESSION['login']}!";
            echo ' <a href="/users/logout">[Выход здесь]</a>';
            if ((+$_SESSION['flags'] & 1) === 0) {
                echo ' Не забудьте подтвердить свою почту! ';
            }
            echo ' <a href="/users/home"> [Домой] </a>';
        } else {
            echo '<a href="/users/login">[Вход здесь]</a>';
        }
    ?><br><br>
    <a href="/help/test">Тесты ||| </a>
    <a href="/help/gitHelp">Git Help | </a>
    <a href="/help/jqueryHelp">jQuery Help | </a>
    <a href="/help/http">Http | </a>
    <a href="/help/CreationLog">CreationLog | </a>
    <a href="/help/jqueryLearn">jQuery Learn ||| </a>
    <a href="/help/docs">Описание ||| </a>
    <a href="/mdn/view">Дневник ||| </a>
    <a href="/users/showRegForm">Регистрация | </a>

<!--    <a href="/users/login">Вход | </a>-->
    <br>

</header>
