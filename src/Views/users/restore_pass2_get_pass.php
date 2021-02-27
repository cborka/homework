<h1> Введите новый пароль </h1>
<?= $params['login']; ?>, введите новый пароль

<form name="user_get_pass" action="/users/restorePassword3" method="POST">
    <table class="form">
        <tr>
            <td><label>Пароль:</label></td>
            <td><input id="password" type="password" name="password" placeholder="***" value=""></td>
        </tr>
        <tr>
            <td><label>Подтверждение пароля:</label></td>
            <td><input id="password2" type="password" name="password2" placeholder="***" value=""></td>
        </tr>
        <tr>
        <tr>
            <td><label></label></td>
            <td><input id="login" type="text" name="login" placeholder="" value="<?= $params['login']; ?>" hidden></td>
        </tr>
            <td></td>
            <td>
                <div class="red" id="hint" ></div>
            </td>
        </tr>
    </table>
</form>
<button name="button_submit" onclick="check_data()">Сохранить пароль</button>

<script>

    //
    //  Проверка правильности занесения данных
    //
    function check_data()
    {
        if (user_get_pass.password.value === "") {
            info("Пароль не заполнен");
            return false;
        }
        if (user_get_pass.password.value !== user_get_pass.password2.value) {
            info("Пароли не совпадают");
            return false;
        }

        info("OK");
        user_get_pass.submit();
    }

    //
    //  Просто выводит сообщение
    //
    function info(str)
    {
        $("#hint").text(str);
    }


</script>