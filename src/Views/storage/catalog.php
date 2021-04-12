<div id="grid-id" class="grid-container-edit">

    <aside class="edit-list" id="list-id">
        Список
    </aside>

    <aside class="edit-element" id="element-id">
        <span>Элемент списка</span><br>
    </aside>

    <div class="tree_box" id="tree-id" hidden></div>
</div>

<script>
    var current_filename = '';
    var result = '';

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
    // Должен быть параметр {id: id, fn: fn}
    function render_element(params = {})
    {
        el = ajax_render('storage/show_file.php', params);
        $("#element-id").html(el);
//        document.getElementById("element-id").innerHTML=el;

        id = params.id;
        $("tr.selected").removeClass("selected");
        $("#tr"+id).addClass("selected");

        if (params.fn !== undefined) {
            current_filename = params.fn;
            setTimeout(delete_tmp_file_t, 10);
        }
    }

    // Показать форму выбора загружаемого файла в правом окне
    function render_element_upload(params = {})
    {
        el = ajax_render('storage/upload_file.php', params);
        $("#element-id").html(el);
     }

    // Удаляем временный файл после показа (после загрузки страницы)
    // delete_tmp_file_t вызывается с помощью setTimeout
    function delete_tmp_file_t() {
        delete_tmp_file(current_filename);
    }
    function delete_tmp_file(filename)
    {
        $.post("/storage/delete_tmp",
            {
                filename: filename,
            },
            function (data, status) {
                //result = data;
            }
        );
    }

    // Удалить файл из хранилища
    function delete_from_storage(id, filename)
    {
        if (!confirm('Вы точно собираетесь удалить файл "'+filename+'" из хранилища?')) {
            alert('No, Uff');
            return;
        }
        $.ajaxSetup({async:false});
        $.post("/storage/delete_from_storage",
            {
                id: id,
                filename: filename
            },
            function (data, status) {
                result = data;
            }
        );

        // Обновить информацию на странице после удаления
        render_list();
        render_element({id: result, fn: 'x1'});
    }
    
    // Скачивание файла
    function load_file(token, filename)
    {
        $.post("/storage/load",
            {
                token: token,
                filename: filename
            },
            function (data, status) {
                //ar_result = data;
            }
        )
    }

    // Копирование ссылки на скачивание в буфер обмена
    function copy_to()
    {
        var copyText = document.getElementById("ref");
        copyText.select();

        document.execCommand("copy");
    }


    // При выборе узла дерева
    // при изменении папки
    function t_on_change (id)
    {
        rec_form.folder_id.value = id.substr(1);
        rec_form.folder.value = get_fullname(document.getElementById(id));
    }

    // При выборе узла дерева
    // при выборе папки во время загрузки
    function t_on_upload (id)
    {
        alert('kkjkfks'+id);
        upload_form.folder_id.value = id.substr(1);
        upload_form.folder.value = get_fullname(document.getElementById(id));
    }


    // Инициализация дерева
    render_tree('2000');

    // Перейти на страницу загрузки нового файла
    // function upload_file() {
    //     window.open('/storage/upload_file','_self',false);
    // }

</script>