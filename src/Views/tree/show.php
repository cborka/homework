<div id="grid-id" class="grid-container-edit">

    <aside class="edit-list" id="list-id">
        Список
    </aside>

    <aside class="edit-element" id="element-id">
        Элемент списка


        <table id="list_table" border="1">
            <thead>
            <tr>
                <td>id</td>
                <td>folder</td>
                <td>list</td>
                <td>flags</td>
                <td>ccount</td>
                <td>name</td>
                <td>path</td>
                <!--        t.id, t.folder, t.list, t.flags, t.ccount, t.name, t.path -->
            </tr>
            </thead>
            <?php foreach ($recs as $rec) { ?>
                <tr id="tr<?= $rec['id']; ?>">
                    <td align="id"> <?= $rec['id']; ?></td>
                    <td align="right"> <?= $rec['folder']; ?></td>
                    <td align="right"> <?= $rec['list']; ?></td>
                    <td align="right"> <?= $rec['flags']; ?></td>
                    <td align="right"> <?= $rec['ccount']; ?></td>
                    <td> <?= $rec['name']; ?></td>
                    <td> <?= $rec['path']; ?></td>
                </tr>
            <?php } ?>

        </table>

        <div>
            <span>Tree</span>
            <ul id="top"  oncontextmenu="show_pm2(); return false;" onkeypress="li_onkeypress()">
                <li id="f0"><span class="li" tabindex="21">Tree</span><ul id="u0"></ul></li>
            </ul>

        </div>

        <span id="info"></span>

<!--        <div class="popup_menu" id="pm0" onmouseleave="hide_pm2(this)" hidden>-->

        <div class="popup_menu" id="pmFolder" hidden>
            <div class="popup_menu_item" id="miAppendFolder" onclick="AppendLi()">Добавить папку</div>
            <div class="popup_menu_item" id="miAppendItem" onclick="AppendLi()">Добавить пункт</div>
            <div class="popup_menu_item" id="miDelete" onclick="DeleteLi()">Удалить</div>
        </div>

        <div class="popup_menu" id="pmItem" hidden>
            <div class="popup_menu_item" id="q3_menu_item" onclick="DeleteLi()">Удалить</div>
        </div>

        <?php //var_dump($params['recs']) ?>
    </aside>

</div>

<script>

    var counter = 1;

    function li_onkeypress() {
        let element = event.target;   // Элемент из которого вызываем меню
        element = element.parentElement;
        alert(element.nodeType + ', ' +element.nodeName + ', ' + element.id + ', ' + element.tagName);
//        alert(element.id);

    }



    //
    // Показать всплывающее меню
    //
    function show_pm2()
    {
        let element = event.target;   // Элемент из которого вызываем меню
        element = element.parentElement;
        let pm_name = 'xxx';

//        alert(element.id);

        if(element.id.substring(0, 1) === 'i') {
            pm_name = 'pmItem';
        } else if(element.id.substring(0, 1) === 'f') {
            pm_name = 'pmFolder';
        } else {
            alert('Ошибка: непонятно какое меню показывать!');
            return;
        }

//        alert(element.nodeType + ', ' +element.nodeName + ', ' + element.id + ', ' + element.tagName);

        let menu  =  document.getElementById(pm_name);
        menu.style.display = 'block';
        menu.parent = element;

        // Располагаем меню по координатам мыши
        menu.style.left = event.clientX - 1 +'px';
        menu.style.top = event.clientY - 1 +'px';
        menu.onmouseleave = hide_pm2;

        return false; // чтобы не всплывало стандартное контекстное меню
    }

    //
    // Спрятать всплывающее меню
    //
    function hide_pm2()
    {
        event.target.style.display = 'none';
    }

    //
    // Добавление пункта или папки
    // Вызов из mi (menu_item) (из пункта всплывающего меню вызванного из листочка)
    //
    function AppendLi()
    {
        let mi = event.target;      // Пункт всплывающиего меню
        let pm = mi.parentElement;  // Всплывающее меню
//        let sp = pm.parent;         // Элемент li - лист дерева из которого вызвали всплывающее меню
        let li = pm.parent;         // Элемент li - лист дерева из которого вызвали всплывающее меню
//        let ul = li.parentElement;  // Элемент ul - папка (ветка) дерева на котором растёт li

//        alert(pm.id + ', ' + li.id + ', ' + ul.id);



        let new_name = prompt('Добавление нового пункта, введите название');

        let li_new = document.createElement('li');
        let span_new = document.createElement('span');
        span_new.className = "li";
        span_new.tabIndex = 20 + counter++;
        li_new.append(span_new);

        // Прицепляем новый элемент к папке
        // li.span.ul
        li.childNodes[1].append(li_new);

        // Настройка нового пункта или папки
        if (mi.id === 'miAppendItem') {
//            li_new.innerHTML = '- ' + new_name;
            span_new.innerHTML = '- ' + new_name;
            li_new.id = 'i'+counter;
        } else if (mi.id === 'miAppendFolder') {
            span_new.innerHTML = '&#10010; ' + new_name;
            li_new.id = 'f'+counter;
            // К новой папке цепляем новыый элемент ul
            let ul_new = document.createElement('ul');
            li_new.append(ul_new);
        }
    }

    //
    // Удаление пункта или папки
    //
    function DeleteLi()
    {
        let mi = event.target;      // Пункт всплывающиего меню
        let pm = mi.parentElement;  // Всплывающее меню
        let li = pm.parent;         // Элемент li - лист дерева из которого вызвали всплывающее меню

        // Проверка: это пункт или папка?
        if (li.id.substring(0, 1) === 'i') {
            li.remove();
        } else if(li.id.substring(0, 1) === 'f') {
            let childsNum = li.childNodes[1].childElementCount;
            if (childsNum > 0) {
                alert("Папка не пуста, содержит " +  childsNum + " элементов.")
            } else {
                li.remove();
            }
        }

        // Скрыть меню
        pm.style.display = 'none';
    }



// ====================================================


    function print_r(el) {
        var s = '';
        s += 'id = ' + el.id + '<br>';
        s += 'type = ' + el.type.type + '<br>';
        s += 'value = ' + el.value + '<br>';
        s += 'content = ' + el.innerHTML + '<br>';
        // for (let node of arr.childNodes) {
        //     alert(node);
        // }
        return s;
    }
    function o2s(obj)
    {
        var ret = 'x';
        for (let key in obj) {
            if (obj[key])
                ret +=  key + ' => ' + obj[key] + '<br>\n';
        }
        return ret;
    }

    // Генеалогическое древо елемента
    function gen_tree(el)
    {
        let elem = el;
        ret = elem.id + '/';
        while(elem = elem.parentNode) { // идти наверх до <html>
            ret += elem.id + '/';
        }
        return ret;
    }



</script>