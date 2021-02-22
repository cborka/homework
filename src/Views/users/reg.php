
Регистрация
<h1> Регистрация в системе </h1>
<form name="userreg" action="/users/reg" method="POST">
    <table class="form">
        <tr>
            <td><label>Логин (имя для входа):</label></td>
            <td><input id="login" type="text" name="login" placeholder="Такое как Alex или Vlad" value="" onchange="check_login_query()">
                <span id="isloginfree"></span></td>
        </tr>
        <tr>
            <td><label>Пароль:</label></td>
            <td><input id="password" type="password" name="password" value=""  onchange="check_data()" ></td>
        </tr>
        <tr>
            <td><label>Подтверждение пароля:</label></td>
            <td><input id="password2" type="password" name="password2" value="" onchange="check_data()" ></td>
        </tr>
        <tr>
            <td><label>А ты не робот? Проверка:</label></td>
            <td><input id="test" type="text" name="test" placeholder="12 х 12 =" value="" onchange="check_data()" ></td>
        </tr>
        <tr>
            <td><label>Полное имя (ФИО):</label></td>
            <td><input id="fullname" type="text" name="fullname" placeholder="Иванов Иван" value="" ></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button name="button_submit" type="submit">Регистрация</button>
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
        document.getElementById("hint").innerHTML="OK";
        userreg.button_submit.disabled = false;
        return true;
    }
    function info(str)
    {
        document.getElementById("hint").innerHTML=str;
    }

    let check_login = function (reply)
    {
        if (reply === "0") {
            show_msg("isloginfree", "Имя '" + userreg.login.value + "' свободно!");
            check_data();
        }
        else
            show_msg("isloginfree", "Имя '"+userreg.login.value+"' уже занято.");
    };

    function check_login_query()
    {
        userreg.button_submit.disabled = true;
        if (userreg.login.value === "") {
            info("Поле 'Login' не заполнено");
            return false;
        }
        // Запрос серверу на проверку не занят ли этот логин
        doQuery ("/users/isLoginFree", check_login, "&login=" + encodeURIComponent(userreg.login.value));
    }
</script>