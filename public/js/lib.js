function libjs() {
   return 'libjs';
}

//
// POST-запрос на выполенние SQL возвращающего одно значение
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

//
// POST-запрос на выполенние update-SQL
//
function sql_update(sql, params)
{
    $.ajaxSetup({async:false});

    sql_result = '';
    $.post("/ajax/sql_update",
        {
            sql: sql,
            params: params
        },
        function (data, status) {
            sql_result = data;
        }
    );

    checkPDOError(sql_result);
    return sql_result;
}

/*
 * Проверка результатов выполнения запроса к БД
 */
function checkPDOError(str) {
    if (str === "PDOError") {
        alert("Ошибка выполнения запроса к базе данных. <br>Подробности смотрите в логах.");
    }
}

/*
 * Асинхронный рендер файла
 * Возвращает строку, которую надо поместить в нужный элемент html
 */
function ajax_render(filename, params)
{
    $.ajaxSetup({async:false});

    ar_result = 'ar_result';
    $.post("/ajax/render_file",
        {
            filename: filename,
            params: params
        },
        function (data, status) {
            ar_result = data;
        }
    );

    return ar_result;
//     return 'vvv';
}

