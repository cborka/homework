function libjs() {
   return 'libjs';
}

//
// POST запрос на выполенние SQL возвращающего одно значение
//
function sql_one(sql, params)
{
    $.ajaxSetup({async:false});

    $.post("/pdo/sql_one",
//    $.post("http://hwvm.ru/users/check_login",
        {
            sql: sql,
            params: params
        },
        function (data, status) {
            return data;
        }
    );

    return '';
}