
Регистрация
<h1> Регистрация в системе </h1>
<form name="userreg" action="/users/reg" method="POST">
    <table class="form">
        <tr>
            <td><label>Логин (имя для входа):</label></td>
            <td><input id="login" type="text" name="login" placeholder="Такое как alex или vlad" value="" onchange="check_data()">
                <span class="red" id="isloginfree"></span></td>
        </tr>
        <tr>
            <td><label>Почта (email):</label></td>
            <td><input id="email" type="email" name="email" placeholder="my@email" value="" onchange="check_data()">
                <span class="red" id="isemailfree"></span></td>
        </tr>
        <tr>
            <td><label>Пароль:</label></td>
            <td><input id="password" type="password" name="password" placeholder="***" value=""  onchange="check_data()" ></td>
        </tr>
        <tr>
            <td><label>Подтверждение пароля:</label></td>
            <td><input id="password2" type="password" name="password2" placeholder="***" value="" onchange="check_data()" ></td>
        </tr>
        <tr>
            <td><label>А вы не робот? Проверка:</label></td>
            <td><input id="test" type="text" name="test" placeholder="12 х 12 =" value="" onchange="check_data()" ></td>
        </tr>
        <tr>
            <td><label>Ваше имя</label></td>
            <td><input id="name" type="text" name="name" placeholder="Иван Иванов" value="" ></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button name="button_submit" type="submit" disabled>Регистрация</button>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <div class="red" id="hint" ></div>
                <div id="erro"></div>

            </td>
        </tr>
    </table>
</form>

<script>

    //
    //  Проверка правильности занесения данных
    //
    function check_data()
    {
        userreg.button_submit.disabled = true;
        if (userreg.login.value.length < 3) {
            info("Поле 'Login' слишком короткое, меньше трёх символов");
        }
        if (!check_login_free()) {
            return false;
        }
        if (!validate_email(userreg.email.value)) {
            info("Формат email неверный");
            return false;
        }
        if (!check_email_free()) {
            return false;
        }
        if (userreg.password.value === "") {
            info("Пароль не заполнен");
            return false;
        }
        if (userreg.password.value !== userreg.password2.value) {
            info("Пароли не совпадают");
            return false;
        }
        if (userreg.test.value !== "144") {
            info("А вы не робот случайно?");
            return false;
        }
        info("OK");
        userreg.button_submit.disabled = false;
        return true;
    }

    //
    //  Не занят ли логин?
    //
    function check_login_free()
    {
        let login = userreg.login.value;

        // Запрос серверу на проверку не занят ли этот логин
//        response = sql_one('SELECT count(*) FROM users WHERE login = \'' + login + '\'');
        response = sql_one('SELECT count(*) FROM users WHERE login = :par1', [login, 'qwerty']);

alert('resp='+response);
        return false;


        if (response !== '0') {
            info("Логин '"+login+"' уже занят.");
            $("#isloginfree").text("Логин занят");
            return false;
        }

        return true;
    }

    //
    //  Есть ли такая почта в БД?
    //
    function check_email_free()
    {
        let email = userreg.email.value;

        // Запрос серверу на проверку не занят ли этот email
        response = sql_one('SELECT count(*) FROM users WHERE email = \'' + email + '\'');

        if (response !== '0') {
            info("Уже есть пользователь с почтой '"+email);
            $("#isemailfree").text("Уже есть такая почта");
            return false;
        }

        return true;
    }

    //
    //  Правильный ли формат email
    //
    function validate_email(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    //
    //  Просто выводит сообщение
    //
    function info(str)
    {
        $("#hint").text(str);
//        document.getElementById("hint").innerHTML=str;
    }


</script>