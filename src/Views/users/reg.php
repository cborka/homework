
Регистрация
<h1> Регистрация в системе </h1>
<form name="userreg" action="/users/reg" method="POST">
    <table class="form">
        <tr>
            <td><label>Логин (имя для входа):</label></td>
            <td><input id="login" type="text" name="login" placeholder="Такое как alex или vlad" value="" maxlength="31" onchange="check_login_free()">
                <span class="red" id="isloginfree"></span></td>
        </tr>
        <tr>
            <td><label>Почта (email):</label></td>
            <td><input id="email" type="email" name="email" placeholder="my@email" value="" maxlength="63" onchange="check_email_free()">
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
            <td><input id="test" type="text" name="test" placeholder="12 х 12 =" value="" maxlength="63" onchange="check_data()" ></td>
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

<!--<button onclick="atest()">atest</button>-->

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
        if (!validate_email(userreg.email.value)) {
            info("Формат email неверный");
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
        if (userreg.test.value != 144) {
            info("А вы не робот случайно?");
            return false;
        }
        if ($("#isloginfree").text() !== '') {
            return false;
        }
        if ($("#isemailfree").text() !== '') {
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
        userreg.button_submit.disabled = true;

        let login = userreg.login.value.trim();

        // Запрос серверу на проверку не занят ли этот логин
        $.ajaxSetup({async:false});
        response = '';
            $.post("/users/isLoginFree",
                {
                    login: String(login)
                },
                function (data, status) {
                    response = data;
                }
            );

        if (response !== '0') {
            $("#isloginfree").text("Логин занят");
        } else {
            $("#isloginfree").text('');
        }

        return check_data();
    }

    //
    //  Есть ли такая почта в БД?
    //
    function check_email_free()
    {
        userreg.button_submit.disabled = true;

        let email = userreg.email.value.trim();

        // Запрос серверу на проверку не занят ли этот email
        $.ajaxSetup({async:false});
        response = '';
        $.post("/users/isEmailFree",
            {
                email: String(email)
            },
            function (data, status) {
                response = data;
            }
        );

        if (response !== '0') {
            $("#isemailfree").text("Уже есть такая почта");
        } else {
            $("#isemailfree").text('');
        }

        return check_data();
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


//     function atest() {
// //        alert('atest');
//         $.ajax({
//             type: "POST",
//             url: "/pdo/sql_one",
//             data: 'select count(*) from test',
// //            dataType: "json",
//             success: function(data) {
//                 alert('success');
//             },
//             error: function(data) {
//                 alert('error');
//             }
//         });
//     }


</script>