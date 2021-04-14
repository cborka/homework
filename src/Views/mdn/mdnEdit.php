<div class="grid-container-edit">

    <aside class="edit-list" id="list-id">
        Список
    </aside>

    <aside class="edit-element" id="element-id">
        Элемент списка
    </aside>

<!--<button onclick="wd()">left</button>-->

</div>


<script>

    render_list();
    render_element({id: '<?= $params['id']; ?>'});

    // Подгоняю высоту элемента под высоту списка, которую жестко задаю в edit.css
    $("#element-id").height($("#list-id").height()+17);


    // Рендер списка
    function render_list(params = {})
    {
        $("#list-id").html(
            ajax_render('mdn/mdnDaysList.php', params)
        );
    }

    // Рендер элемента списка. Показ
    function render_element(params = {})
    {
//        let el = ajax_render('mdn/mdnDayElement.php', params);
        $("#element-id").html(
            ajax_render('mdn/mdnDayElement.php', params)
        );

        id = params.id;
        $("tr.selected").removeClass("selected");
        $("#tr"+id).addClass("selected");
    }

    // Рендер элемента списка. Редактирование
    function render_element_edit(params = {})
    {
//        let el = ajax_render('mdn/mdnDayElementEdit.php', params);
        $("#element-id").html(
            ajax_render('mdn/mdnDayElementEdit.php', params)
        );
    }


    function add_record() {
        $("tr.selected").removeClass("selected");
        render_element_edit({id: 0});
    }

    function save_record(frm) {
        id = frm.id.value;
        dt = frm.dt.value;
        header = frm.header.value;
        content = frm.content.value;
        password = frm.password.value;

        if (password !== '21') {
            alert ('Неверный пароль!');
            return;
        }

        if (id === '0') {
            sql = 'INSERT INTO my_daily_news(dt, header, content) VALUES (?, ?, ?)';
            ret = sql_update(sql, [dt, header, content]);
        } else {
            sql = `
                UPDATE my_daily_news SET
                    dt = ?,
                    header = ?,
                    content = ?
                WHERE
                    id = ? `;

            ret = sql_update(sql, [dt, header, content, id]);
        }

        if (ret !== '1') {
            alert('Странненько, так не должно быть в принципе, ret = ' + ret);
        }

        if (id === '0') {
            id = sql_one('SELECT id FROM my_daily_news WHERE dt = ?', [dt]);
            render_list();
        }

        render_element({id});
    }


    /*
     * Подогнать высоту списка к высоте формы элемента списка
     */
    function set_list_height()
    {
        let h = $("#element-id").height();

        $("#info").text("Высота элемента = "+h);

        if (h < 300) h = 300;

        $("#list-id").height(h);
    }


</script>


<?php
