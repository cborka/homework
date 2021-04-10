<?php
f_tree_show();

// Оборачиваю включаемые файлы в функции чтобы не было конфликтов переменных.
function f_tree_show()
{
    ?>
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
    <div class="tree_box" id="tree_box" hidden>
        <div>
            <nav id="navTreeTop">
            <button id="btnAppendItem" tabindex="15" onclick="append_node(tree_current_li, false)" title="Добавить пункт"> + </button>
            <button id="btnAppendFolder" tabindex="16" onclick="append_node(tree_current_li, true)" title="Добавить папку"> ++ </button>
            <button id="btnDelete" tabindex="17" onclick="delete_node(tree_current_li)" title="Удалить узел (Delete)"> - </button>
            <button id="btnExpand" tabindex="18" onclick="expand_folder(tree_current_li)" title="Открыть папку (Стрелка вправо, ПКМ)"> > </button>
            <button id="btnHide" tabindex="19" onclick="hide_folder(tree_current_li)" title="Закрыть папку (Стрелка влево, ПКМ)"> < </button>
            <button id="btnRename" tabindex="20" onclick="rename_node(tree_current_li)" title="Переименовать узел (F2)"> ~ </button>
            </nav>
        </div>
        <div id="f0" class="tree">
            <ul id="root" oncontextmenu="dblclick_expand_folder(); return false;" ondblclick="return_node_id(true)">
                <li id="f1"><span id="span1" class="li" tabindex="27" onfocus="remember_me()">root</span><ul></ul></li>
            </ul>
        </div>
        <div>
            <nav id="navTreeBottom">
                <button id="btnOK" onclick="return_node_id(true)"  tabindex="776">Выбор</button>
                <button id="btnEsc" onclick="return_node_id(false)" onkeydown="next_focus(e)" tabindex="777">Отмена</button>
            </nav>
        </div>
    </div>


    <span id="info"></span>

    <script>



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

<?php } ?>