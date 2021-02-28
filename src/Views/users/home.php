<h1>Добро пожаловать</h1>

<a href="/users/account">Личный кабинет</a><br>
<a href="/users/changePassword">Изменить пароль</a>
<?php
echo " <br>" . Lib::var_dump1($_POST) . '<br>' . Lib::var_dump1($_SESSION);
