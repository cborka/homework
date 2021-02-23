
Регистрация
<h1> Регистрация в системе </h1>
<form name="userreg" action="/users/reg" method="POST">
    <table class="form">
        <tr>
            <td><label>Логин (имя для входа):</label></td>
            <td><input id="login" type="text" name="login" placeholder="Такое как alex или vlad" value="" onchange="check_login_query()">
                <span id="isloginfree"></span></td>
        </tr>
        <tr>
            <td><label>Почта:</label></td>
            <td><input id="email" type="email" name="email" placeholder="my@email" value="" onchange="check_email_query()">
                <span id="isemailfree"></span></td>
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
            <td><label>А ты не робот? Проверка:</label></td>
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
                <div id="hint" ></div>
                <div id="erro"></div>

            </td>
        </tr>
    </table>
</form>

<script>
    function check_data()
    {
        userreg.button_submit.disabled = true;
        if (userreg.login.value === "")
        {
            info("Поле 'Login' не заполнено");
            return false;
        }
        if (userreg.email.value === "")
        {
            info("Email не заполнен");
            return false;
        }
        if (userreg.password.value === "")
        {
            info("Пароль не заполнен");
            return false;
        }
        if (userreg.password.value !== userreg.password2.value)
        {
            info("Пароли не совпадают");
            return false;
        }
        if (userreg.test.value !== "144")
        {
            info("А не робот ли ты?");
            return false;
        }
        info("OK");
        userreg.button_submit.disabled = false;
        return true;
    }

    function info(str)
    {
        document.getElementById("hint").innerHTML=str;
    }

    function check_login_query()
    {
        let login = userreg.login.value;

        if (userreg.login.value === "") {
            info("Поле 'Login' не заполнено");
            return false;
        }

        // Запрос серверу на проверку не занят ли этот логин
        response = sql_one('SELECT count(*) FROM users WHERE login = \'' + login + '\'');

        if (response !== '0') {
            info( "Логин '"+login+"' уже занят.")
            $("#isloginfree").text("Логин занят")
        } else {
            check_data();
        }

//        alert('resp = '+response);

//        doQuery ("/users/isLoginFree", check_login, "&login=" + encodeURIComponent(userreg.login.value));
    }

    function check_emailn_query()
    {
        if (!check_data()) {
            return false;
        }
        userreg.button_submit.disabled = true;
        if (userreg.login.value === "") {
            info("Поле 'Login' не заполнено");
            return false;
        }
        // Запрос серверу на проверку не занят ли этот логин
//        doQuery ("/users/isLoginFree", check_login, "&login=" + encodeURIComponent(userreg.login.value));
    }

    function show_msg(str) {
        info();
    }

</script>