<div id="grid-id" class="grid-container-edit">

    <aside class="edit-list" id="list-id">
        Список
        <!-- Дерево в виде таблицы -->
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
            <?php
//            $records = json_decode($recs, true);
            foreach ($recs as $rec) { ?>
                <tr id="tr<?= $rec['id']; ?>">
                    <td align="id"> <?= $rec['id']; ?></td>
                    <td align="right"> <?= $rec['folder']; ?></td>
                    <td align="right"> <?= $rec['list']; ?></td>
                    <td align="right"> <?= $rec['flags']; ?></td>
                    <td align="right"> <?= $rec['ccount']; ?></td>
                    <td>
                        <?= $rec['name']; ?>
                    </td>
                    <td>
                        <?= $rec['path']; ?>
                    </td>
                    <td>
                        <?= $rec['path'] . $rec['name']; ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <button onclick="draw_folder('ul1', '')">DATA</button>

    </aside>

    <aside class="edit-element" id="element-id">
        Элемент списка


<!--
        СТРУКТУРА ДЕРЕВА

        // ПУНКТЫ
        // У пункта один потомок - SPAN,
        // id пункта начинается с буквы i, а id папки с f
        //   по этому признаку смотрю какое всплывающее меню показывать
        //   (этот признак я собирался использовать для различения и при движении по дереву, но не стал,
        //    папки и пункты различаю по количеству прямых потомков, 2 или 1
        //    возможно со всплывающим меню это тоже надо переделать, я подумаю)
        <li id="i1">
            <span class="li" tabindex="21">Tree</span>
        </li>

        // ПАПКИ
        // у папок по 2 потомка: SPAN и UL
        //   в UL будет находится веточка дерева, то есть элементы LI (пункты и папки).
        // id папки начинается с f
        <li id="f0">
            // Фокус делаем на SPAN
            //   для этого у него есть tabindex,
            //   хотя в перемещении по дереву стрелками он не участвует
            <span class="li" tabindex="21">Tree</span>
            <ul id="u0"></ul>
        </li>

        // В реальной жизни всё это без пробелов,
        //   так как пробелы это компоненты html,
        //   которые мешают работе с деревом из JavaScript
        // У корневой папки id="f0", это пока изспользуется в логике программы
-->
        <div id="f0" class="tree">
            <span>Tree</span>
            <ul id="root" oncontextmenu="show_pm2(); return false;">
                <li id="f0"><span class="li" tabindex="21">/</span><ul id="ul1"></ul></li>
            </ul>

        </div>

        <span id="info"></span>

<!--        <div class="popup_menu" id="pm0" onmouseleave="hide_pm2(this)" hidden>-->

        <!--Всплывающее меню для ПАПКИ -->
        <div class="popup_menu" id="pmFolder" hidden>
            <div class="popup_menu_item" id="miAppendFolder" onclick="AppendLi()">Добавить папку</div>
            <div class="popup_menu_item" id="miAppendItem" onclick="AppendLi()">Добавить пункт</div>
            <div class="popup_menu_item" id="miExpand" onclick="ExpandLi()">Развернуть</div>
            <div class="popup_menu_item" id="miRename" onclick="RenameLi()">Переименовать</div>
            <div class="popup_menu_item" id="miDelete" onclick="DeleteLi()">Удалить</div>
        </div>

        <!--Всплывающее меню для ПУНКТА-->
        <div class="popup_menu" id="pmItem" hidden>
            <div class="popup_menu_item" id="q3_menu_item" onclick="DeleteLi()">Удалить</div>
        </div>

        <?php //var_dump($params['recs']) ?>
    </aside>

<!--    <button onclick="draw_folder('f0')">DATA</button>-->

</div>

<script>

    var counter = 1; // Глобальный счетчик для формирования tabIndex создаваемых компонентов дерева



    //
    // Рисуем веточку дерева
    //
    function draw_folder (ul_id, target_ul = '')
    {
        let ul;
        let data;

        // Читаем данные из БД
        $.ajaxSetup({async:false});
        var result = '';
        $.post("/tree/getFolder",
            {
                folder: ul_id
            },
            function (res, status) {
                result = res;
            }
        );

        if (result === 'PDOError') {
            alert('Ошибка чтения из Базы Данных');
            return;
        }

//        data = <?php //echo json_encode($recs); ?>;
        data = JSON.parse(result);

        if (target_ul === 'root') {
            ul = document.getElementById(target_ul);
        } else {
            ul = document.getElementById(ul_id);
        }

        // Очищаем элемент к которому будем цеплять веточку
        while (ul.childElementCount > 0) {
             ul.childNodes[0].remove();
        }

        document.getElementById('info').innerHTML = '';
//        SELECT t.id, t.folder, t.list, t.flags, t.ccount, t.name, t.path
        for (let i = 0; i < data.length; i++) {
            document.getElementById('info').innerHTML += data[i].name + data[i].id + '<br>';

            let li_new = document.createElement('li');
            let span_new = document.createElement('span');
            span_new.className = "li";
            span_new.tabIndex = 20 + data[i].id;
            span_new.innerHTML = data[i].name;

            li_new.append(span_new);
            ul.append(li_new);

            let flag = data[i].flags;
            if ((flag & 1) === 1) { // Это пункт
                li_new.id = 'i'+data[i].id;
//                span_new.innerHTML =  li_new.id + '- ' + span_new.innerHTML;
            } else {                // Это папка
                li_new.id = 'f'+data[i].id;
//                span_new.innerHTML = li_new.id + '&#10010; ' + span_new.innerHTML;

                // К новой папке цепляем новыый элемент ul
                let ul_new = document.createElement('ul');
                ul_new.id = 'ul'+data[i].id;
                li_new.append(ul_new);
            }
        }
    }


    //
    //  При отжатии клавиши
    //
    top.onkeyup = li_onkeyup;
    function li_onkeyup(e) {

        let element = event.target;         // Элемент из которого вызываем меню SPAN
        element = element.parentElement;    // LI

        switch (e.code) {
            case 'ArrowUp':
                move_up(element);
                break;
            case 'ArrowDown':
                move_down(element);
                break;
            case 'ArrowRight':
                expand_folder(element);
                break;
            case 'ArrowLeft':
                hide_folder(element);
                break;
            case 'F2':
                rename_node(element);
                break;
         // default:
         //    alert(e.code);
        }

//        alert(element.nodeType + ', ' +element.nodeName + ', ' + element.id + ', ' + element.tagName + '=' + e.code);
//        alert(element.id);
    }

    // У пункта один потомок - SPAN,
    // у папки 2 потомка: SPAN и UL, в котором находится содержимое - LI[].
    // Фокус делаем на SPAN

    //
    // Нажата стрелка вверх
    //
    function move_up(el)
    {
        // Потолок
        if (el.id === 'f0') {
            return;
        }

        if (el.previousElementSibling) {                // Если есть старший брат
             el = find_last(el.previousElementSibling); //  то переходим последнего потомка его последнего потомка ...
             el.firstElementChild.focus();
             // el.SPAN.Focus()
        }
        else {                                          // Если нет старшего брата
            el.parentElement.parentElement.firstElementChild.focus(); // Переходим на родителя
           //el.UL.LI.SPAN.Focus()
        }
    }
    // Если нет пустой строки между функциями, значит нижняя вызывается из верхней и больше ниоткуда
    //
    // Найти последнего потомка (сына или внука или даже пра...правнука, как получится)
    //
    function find_last(el)
    {
        // == 1 - Это пункт, а не папка, т.к. отсутствует второй потомок, который UL,
        // == 0 - непонятно что, но ладно, всё равно возвращаю то, что пришло
        if (el.childElementCount <= 1) {
            return el;
        }

        // Это папка без потомков (пустой UL)
        if (el.childNodes[1].childElementCount === 0) {
            return el;
        }

        // Папка с потомками, всё сначала (рекурсия)
        return find_last(el.childNodes[1].lastElementChild);
    }

    //
    // Нажата стрелка вниз
    //
    function move_down(el)
    {
        // Если есть потомки (если прямой потомок - папка И она не пустая)
        if ((el.childElementCount > 1) && (el.childNodes[1].childElementCount > 0)) {
            // Переходим на первого потомка
            el.childNodes[1].firstElementChild.firstElementChild.focus();
            // el.UL.LI.SPAN.focus();
        } else {
            // Возвращаемся вверх пока не найдем предка у которого есть потомки ниже
            el = find_next(el);
            // И если нашли, то переходим на первого потомка этого предка
            if(el !== null) {
                el.firstElementChild.focus();
                // el.SPAN.Focus()
            } else {
//                alert('// Уже в самом низу');
            }
        }
    }
    //
    // Найти ближайшего предка с потомками и перейти на первого потомка этого предка
    //
    function find_next(el)
    {
        // Есть ли младшие братья?
        if (el.nextElementSibling) {
            return el.nextElementSibling;
        }

        // Если нет младших братьев, то ищем младших братьев родителя
        el = el.parentElement.parentElement;
        if (el.id === 'f0') { // Адам, выше только DIV
            return null;
        } else {
            return find_next(el);
        }
    }


    //
    // Показать всплывающее меню
    //
    function show_pm2()
    {
        let element = event.target;         // Элемент из которого вызываем меню SPAN
        element = element.parentElement;    // LI

        let pm_name = '';                   // Определяю переменную здесь (из-за области видимости LET)

        if(element.id.substring(0, 1) === 'i') {
            pm_name = 'pmItem';
        } else if(element.id.substring(0, 1) === 'f') {
            pm_name = 'pmFolder';
        } else {
            // Бывает если нажали на значок ul,
            // это родтельское UL и непонятно какое LI (кого из потомков) брать, поэтому игнорируем
//            alert('Ошибка: непонятно какое меню показывать! element.id = ' + element.id);
            return;
        }

//        alert(element.nodeType + ', ' +element.nodeName + ', ' + element.id + ', ' + element.tagName);

        let menu = document.getElementById(pm_name);
        menu.style.display = 'block';
        menu.parent = element;
        menu.onmouseleave = hide_pm2;

        // Располагаем меню по координатам мыши
        menu.style.left = event.clientX - 1 +'px';
        menu.style.top = event.clientY - 1 +'px';

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
    // Рисуем веточку дерева
    //

    // При нажатии стрелки вправо
    function expand_folder(el) {
        // Если это папка
        if (el.childElementCount > 1) {
            draw_folder(el.childNodes[1].id);
        }
    }
    // При выборе пункта всплывающего меню
    function ExpandLi () {
        let mi = event.target;      // Пункт всплывающиего меню
        let pm = mi.parentElement;  // Всплывающее меню
        let li = pm.parent;         // Элемент li - лист дерева из которого вызвали всплывающее меню

        // Скрыть всплывающее меню
        pm.style.display = 'none';

        draw_folder(li.childNodes[1].id);
    }

    //
    // Спрятать веточку дерева при нажатии стрелки влево
    //
    function hide_folder(el) {
        // Если это папка
        if (el.childElementCount > 1) {
            let ul = el.childNodes[1];
            while (ul.childElementCount > 0) {
                ul.childNodes[0].remove();
            }
        }
    }

    //
    // Добавление пункта или папки
    // Вызов из mi (menu_item) (из пункта всплывающего меню вызванного из листочка)
    //
    function AppendLi()
    {
        let mi = event.target;      // Пункт всплывающиего меню
        let pm = mi.parentElement;  // Всплывающее меню
        let li = pm.parent;         // Элемент li - лист дерева из которого вызвали всплывающее меню
//        let ul = li.parentElement;  // Элемент ul - папка (ветка) дерева на котором растёт li

        let new_id = 0;
        let flags = 1; // Признак Пункта в БД
        let msg = 'нового пункта';
//        alert(pm.id + ', ' + li.id + ', ' + ul.id);

        // Скрыть всплывающее меню
        pm.style.display = 'none';


//        let new_name = 'node_' + (counter + 1); // Для отладки

        // if (mi.id === 'miAppendItem') {
        //     //
        // } else
        if (mi.id === 'miAppendFolder') {
            flags = 2; // Признак Папки в БД
            msg = 'новой папки';
        }

        let new_name = prompt('Добавление ' + msg + ', введите название');
        if(!new_name) { // Нажали отмену
            return;
        }


        // Вставляем запись в таблицу БД
        $.ajaxSetup({async:false});
        var result = '';
        $.post("/tree/appendNode",
            {
                folder: li.id,
                flags: flags,
                name: new_name
            },
            function (data, status) {
                result = data;
            }
        );

        if (result === 'PDOError') {
            alert('Ошибка записи в Базу Данных, возможно такое имя уже есть в этой папке.');
            return;
        } else {
            new_id = result;
        }

        let li_new = document.createElement('li');
        let span_new = document.createElement('span');
        span_new.className = "li";
        span_new.tabIndex = 20 + counter++;
        li_new.append(span_new);

        // Прицепляем новый элемент к папке. Это папка, так как вызов этой функции возможен только из папки
        // li.ul.append()
        li.childNodes[1].append(li_new);

        // Настройка нового пункта или папки
        if (mi.id === 'miAppendItem') {
            li_new.id = 'i'+new_id;
            span_new.innerHTML = '- ' + new_name;
        } else if (mi.id === 'miAppendFolder') {
            li_new.id = 'f'+new_id;
            span_new.innerHTML = '&#10010; ' + new_name;

            // К новой папке цепляем новыый элемент ul
            let ul_new = document.createElement('ul');
            ul_new.id = 'ul'+new_id;
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

        // Скрыть меню
        pm.style.display = 'none';

        // Удаление из БД
        $.ajaxSetup({async:false});
        var result = '';
        $.post("/tree/deleteNode",
            {
                node: li.id
            },
            function (data, status) {
                result = data;
            }
        );

        if (result === 'PDOError') {
            alert('Ошибка удаления записи из Базы Данных.');
            return;
        }

        if (result !== '0') {
            alert("Папка не пуста, содержит " +  result + " элементов.");
            return;
        }

        li.remove();
    }

    //
    // Переименовать узел
    //
    // При выборе пункта всплывающего меню
    function RenameLi () {
        let mi = event.target;      // Пункт всплывающиего меню
        let pm = mi.parentElement;  // Всплывающее меню
        let li = pm.parent;         // Элемент li - лист дерева из которого вызвали всплывающее меню

        rename_node(li);
    }
    // При нажатии F2
    function rename_node(el) {

        span = el.childNodes[0];

//        let new_name = 'zxcv';
        let new_name = prompt('Введите новое имя для', span.innerHTML);
        if(!new_name) { // Нажали отмену
            return;
        }

        // Переименовать узел в БД
        $.ajaxSetup({async:false});
        var result = '';
        $.post("/tree/renameNode",
            {
                node: el.id,
                name: new_name
            },
            function (data, status) {
                result = data;
            }
        );

        if (result === 'PDOError') {
            alert('Ошибка переименования узла в Базе Данных');
            return;
        }

        span.innerHTML = new_name;
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