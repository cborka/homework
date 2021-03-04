<?php


global $mypdo;

$result = $mypdo->sql_one('SELECT name FROM users WHERE login = ?', ['nubasik13']);

  echo $result;
  echo $z;
  echo 'hello';


?>




<button onclick="oc()">click</button>
<script>
    // тут скрипты не работают
</script>
