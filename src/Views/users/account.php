<h1> Личный кабинет </h1>
<?= $_SESSION['login']; ?> зарегистрирован <?= $_SESSION['created_at']; ?>
<form name="useraccount" action="/users/saveAccount" method="POST">
    <table class="form">
        <tr>
            <td><label>Ваше имя</label></td>
            <td><input id="name" type="text" name="name"  value="<?= $_SESSION['name']; ?>" ></td>
        </tr>
        <tr>
            <td><label>День рожденья</label></td>
            <td><input id="birthday" type="date" name="birthday"  value="<?= $_SESSION['birthday']; ?>" ></td>
        </tr>
        <tr>
            <td><label>Почта (email):</label></td>
            <td><input id="email" type="email" name="email" value="<?= $_SESSION['email']; ?>" maxlength="63" onchange="check_email_free()">
                <span class="red" id="isemailfree"></span></td>
        </tr>
        <tr>
        <tr>
            <td><label>Телефон</label></td>
            <td><input id="phone" type="text" name="phone" value="<?= $_SESSION['phone']; ?>" ></td>
        </tr>
        <tr>
            <td><label>О себе</label></td>
            <td><textarea name="notes" rows="10" cols="80" maxlength="2047"><?= $_SESSION['notes'] ?></textarea></td>
        </tr>
            <td></td>
            <td>
                <button name="button_submit" type="submit">Сохранить</button>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <div class="red" id="hint" ></div>
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
        if (!validate_email(userreg.email.value)) {
            info("Формат email неверный");
            return false;
        }
        if ($("#isemailfree").text() !== '') {
            return false;
        }

        info("");
        return true;
    }

    //
    //  Есть ли такая почта в БД?
    //
    function check_email_free()
    {
        let email = useraccount.email.value.trim();

        // Запрос серверу на проверку не занят ли этот email
        response = sql_one('SELECT count(*) FROM users WHERE email = ? AND login <> ?', [String(email), String("<?= $_SESSION['login']; ?>")]);

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

</script>