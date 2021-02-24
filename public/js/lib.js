function libjs() {
   return 'libjs';
}

function sql_one(sql, params)
{
    $.ajaxSetup({async:false});
    response = '';
    $.post("/pdo/sql_one",
//    $.post("http://hwvm.ru/users/check_login",
        {
            params: params,
            sql: sql
        },
        function (data, status) {
            response = data;
        }
    );




    // Если ошибка PDO
    if (response.substr(0, 14) === 'MyPdo->sql_one') {
        alert(response);
        return '';
    }

    return response;
}