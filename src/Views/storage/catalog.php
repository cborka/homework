<div class="grid-container-edit">

    <aside class="edit-list" id="list-id">
        Список
    </aside>

    <aside class="edit-element" id="element-id">
        Элемент списка
    </aside>

</div>

<script>
    render_list();
    render_element({id: <?= $params['id']; ?>});

    // Подгоняю высоту элемента под высоту списка, которую жестко задаю в edit.css
    $("#element-id").height($("#list-id").height()+17);

    // Рендер списка
    function render_list(params = {})
    {
        $("#list-id").html(
            ajax_render('storage/catalog_list.php', params)
        );
    }

    // Рендер элемента списка. Показ
    // Должен быть параметр {id: id}
    function render_element(params = {})
    {
        el = ajax_render('storage/show_file.php', params);
        $("#element-id").html(el);

        id = params.id;
        $("tr.selected").removeClass("selected");
        $("#tr"+id).addClass("selected");
    }

    // А после показа удаляю.
//    setTimeout(delete_file, 3000);
    function delete_file()
    {
        $.post("/storage/delete",
            {
                filename: "<?= $filename; ?>",
            },
            function (data, status) {
                //ar_result = data;
            }
        )
    }


    // Перейти на страницу загрузки нового файла
    function upload_file() {
        window.open('/storage/upload_file','_self',false);
    }

</script>