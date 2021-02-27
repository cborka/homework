<h1> Восстановление пароля </h1>
<h3>Укажите ваши почту и/или логин</h3>

<form name="user_restore_pass1" action="/users/restorePassword1" method="POST">
    <table class="form">
        <tr>
            <td><label>Логин</label></td>
            <td><input id="login" type="text" name="login" placeholder="Введите логин" value="" maxlength="31"">
        </tr>
        <tr>
            <td><label>Eemail</label></td>
            <td><input id="email" type="email" name="email" placeholder="Введите email" value="" maxlength="63">
        </tr>
        <tr>
            <td></td>
            <td>
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
<button name="button_submit" onclick="check_data()">Восстановить пароль</button>

<script>


    //
    //
    //
    function check_data()
    {
         let login = user_restore_pass1.login.value.trim();
         let email = user_restore_pass1.email.value.trim();

//        alert('login = '+login +', email = ' + email)

        if (login !== '') {
            if (email === '') {
                response = sql_one('SELECT flags & 1 FROM users WHERE login = ?', [String(login)]);
            } else {
                response = sql_one('SELECT flags & 1 FROM users WHERE login = ? AND email = ?', [String(login), String(email)]);
            }
        } else if (email !== '' ) {
            response = sql_one('SELECT flags & 1 FROM users WHERE email = ?', [String(email)]);
        } else {
            // ничего не ввели
            return;
        }

        if (response === '1') {
            // Пользователь найден
            // Показать форму смены пароля
            user_restore_pass1.submit();
        } else  if (response === '0') {
            // Пользователь найден, почта не подтверждена
            alert ("К сожалению, ваша почта не подтверждена. <br>Смена пароля невозможна.");
        } else {
            // Пользователь не найден
            alert ("Пользователя с такими данными не найдено. <br>Попробуйте ввести что-то одно, почту или логин.");
        }
     }

</script>