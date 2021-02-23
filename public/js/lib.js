function libjs() {
   return 'libjs';
}

function sql_one(sql) {

    $.ajaxSetup({async:false});
    response = '';
    $.post("/pdo/sql_one",
//    $.post("http://hwvm.ru/users/check_login",
        {
            sql: sql
        },
        function (data, status) {
            response = data;
        });

    if (response.substr(0, 12) === 'PDOException') {
        alert(response);
        return '';
    }

    return response;
}