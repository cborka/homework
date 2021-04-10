<div class="grid-container-edit">

    <aside class="edit-list" id="list-id">
        Список
    </aside>

    <aside class="edit-element" id="element-id" oncontextmenu="tree_show_on_click('2', 'Тест');return false;">
        <span>Элемент списка</span><br>
    </aside>


</div>

<script>
    var current_filename = '';
    var result = '';

//     render_test();

//     function render_test()
//     {
//         $("#div_test").html(
//             ajax_render('tree/show_tree.php', ['36'])
// //            ajax_render('tree/render_test.php', [])
//         );
//
//         let menu = document.getElementById('div_test');
//         menu.style.position = "fixed";
//         menu.style.display = 'block';
//
//         // menu.onmouseleave = function () {
//         //     event.target.style.display = 'none';
//         // };
//
//         // Располагаем меню по координатам мыши
//         menu.style.left = event.clientX - 1 +'px';
//         menu.style.top = event.clientY - 1 +'px';
//
//         return false; // чтобы не всплывало стандартное контекстное меню надо возвращать false НА МЕСТЕ ВЫЗОВА МЕНЮ!!!
//     }
//

    //=======================================


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
    function tree_on_selection (id) {
        alert('=== ' + id);
    }

    // Инициализация дерева
    render_tree('2000');

    // Перейти на страницу загрузки нового файла
    // function upload_file() {
    //     window.open('/storage/upload_file','_self',false);
    // }

</script>