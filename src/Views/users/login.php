<h1> Вход </h1>
<form name="userlogin" action="/users/userLogin" method="POST">
    <table class="form">
        <tr>
            <td><label>Логин</label></td>
            <td><input id="login" type="text" name="login" placeholder="login" value="" maxlength="31"></td>
        </tr>
        <tr>
            <td><label>Пароль</label></td>
            <td><input id="password" type="password" name="password" placeholder="***" value=""></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button name="button_submit" type="submit">Вход</button>
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


<script>

</script>