function libjs() {
   return 'libjs';
}

//
// POST запрос на выполенние SQL возвращающего одно значение
//
function sql_one(sql, params)
{
    $.ajaxSetup({async:false});

    sql_one_result = '';
    $.post("/ajax/sql_one",
        {
            sql: sql,
            params: params
        },
        function (data, status) {
            sql_one_result = data;
        }
    );

    checkPDOError(sql_one_result);
    return sql_one_result;
}

/*
 * Проверка результатов выполнения запроса к БД
 */
function checkPDOError(str) {
    if (str === "PDOError") {
        alert("Ошибка выполнения запроса к базе данных. <br>Подробности смотрите в логах.");
    }
}
