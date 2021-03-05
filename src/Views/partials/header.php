<header>
    <div class="grad">
        <a href="/users/home"; style="color: blueviolet; text-decoration: none;">
            <h1 class="title">Homework</h1>
        </a>
        <?php
            if(isset($_SESSION['name'])) {
                echo "Привет, {$_SESSION['name']}!";

//                echo ' <a href="/users/logout">[Выход здесь]</a>';

                if ((+$_SESSION['flags'] & 1) === 0) {
                    echo ' Не забудьте подтвердить свою почту! ';
                }

                echo ' ';
//                echo ' <a href="/users/home"> [Домой] </a>';
            } else {
                echo 'Добро пожаловать!';
//                echo 'Добро пожаловать! <a href="/users/login">[Вход здесь]</a>';
            }
        ?>
        <br>
        <br>
        <?= \Models\Users\RegUser::getUserMenu(); ?>

    </div>

</header>
