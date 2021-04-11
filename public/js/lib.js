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

// Получение координат элемента
function getCoords(elem) // кроме IE8-
{
    var box = elem.getBoundingClientRect();

    return {
        top: box.top + pageYOffset,
        left: box.left + pageXOffset
    };
}


// ================================== TREE ==================================
/*
 *  Для работы с деревом на странице должен быть размещён следующий элемент
 *      <div class="tree_box" id="tree-id" hidden></div>
 *
 * Дерево должно быть инициализировано функцией
 *      render_tree('2000');
 *
 * Для показа дерева используется следующий обработчик
 *       on...="tree_show_on_click('2', 'Тест');return false;"
 * где
 *      2 - это id веточки дерева используемой как корень,
 *      'Тест' - желаемое название корневой веточки, его нельзя будет изменить
 *
 * Также должна быть Функция tree_on_selection которая получает выбранный узел дерева
 *     function tree_on_selection (id) {
 *         alert(id);
 *      }
 */



// При показе в модальном окне не должны уходить из него по Tab
// поэтому верхние кнопки буду иметь tabindex от 15, нижние заканчиваться на 777,
// а между ними будут узлы дерева, вряд ли их будет больше, хотя присваивать tabindex надо по другому
// чтобы как по стрелкам ходить, а так получится вразброс, знаю как сделать, но пусть пока так
let tree_next_tabindex = '33';
let tree_current_li; // Здесь запомнинаме узел на котором фокусиремся чтобы затем возвратить его

//
// Показ дерева по координатам мыши по правой кнопке мыши
//
function tree_show_on_click(id, name='')
{
    // if (event.target.id !== "element-id") {
    //     return;
    // }

    if (document.getElementById('cover-div')) {
        return; // Пытаемся вызвать второй раз
    }

    let menu = document.getElementById('tree-id');
    menu.style.position = "absolute";
    menu.style.display = 'block';

    showCover();

    // menu.onmouseleave = function () {
    //     event.target.style.display = 'none';
    // };

    // Располагаем меню по координатам мыши
    menu.style.left = event.clientX - 1 +'px';
    menu.style.top = event.clientY - 1 +'px';

    // menu.style.display = 'block';
    menu.hidden = false;

    show_folder(id, name);
}

//
//  Дерево нарисовано, но пока скрыто
//
function render_tree(id)
{
    $("#tree-id").html(
        ajax_render('tree/show_tree.php', [id])
    );

}


// Показать полупрозрачный DIV, чтобы затенить страницу
// (форма располагается не внутри него, а рядом, потому что она не должна быть полупрозрачной)
function showCover() {
    let coverDiv = document.createElement('div');
    coverDiv.id = 'cover-div';

    // убираем возможность прокрутки страницы во время показа модального окна с формой
    document.body.style.overflowY = 'hidden';

    document.body.append(coverDiv);
}
function hideCover() {
    document.getElementById('cover-div').remove();
    document.body.style.overflowY = '';
}

//
// Завершаем и возвращаем выбранный узел
//
function return_node_id(is_ok)
{
    hideCover();
    document.getElementById('tree_box').hidden = true;
    document.getElementById('tree-id').hidden = true;
    document.getElementById('tree_box').style.zIndex = -1;
    document.getElementById('tree-id').style.zIndex = -1;

//    alert(get_fullname(tree_current_li));
    if(tree_on_selection) {
        tree_on_selection(is_ok ? tree_current_li.id : undefined);
    }
}

//
// Запомнить узел на котором фокусиремся
//
function remember_me()
{
    let element = event.target;
    tree_current_li = element.parentElement;    // LI
}

//
// Сформировать fullname для узла дерева
//
function get_fullname(li)
{
    let elem = li;
    let ret = '/' + elem.firstElementChild.innerHTML;
    while(elem = elem.parentNode) { // идти наверх до <html>
        if (elem.tagName === 'LI')
           ret = '/' + elem.firstElementChild.innerHTML + ret;

        if (elem.id === 'f0') { // Почему не условие цикла? Перестраховка от зацикливания.
            return ret;
        }
    }
    return ret;
}

//
//  Зацикливаю переход по Tab внутри дерева
//
document.getElementById('btnEsc').onkeydown = function(e)
{
    if (e.key == 'Tab' && !e.shiftKey) {
        document.getElementById('btnAppendItem').focus();
        return false;
    }
};
document.getElementById('btnAppendItem').onkeydown = function(e)
{
    if (e.key == 'Tab' && e.shiftKey) {
        document.getElementById('btnEsc').focus();
        return false;
    }
};

//
// Рисуем веточку дерева от корня
//
function show_folder (id, name='')
{
    // Настройка корневого ul
    let root = document.getElementById('root'); // Корневой ul
    let f1 = root.children[0];                // li, которая содержит корневую веточку, в которой есть
    let span = f1.children[0];                //   span - название и
    let ul = f1.children[1];                  //   ul - почка следующей веточки

    // По идее здесь надо находить название по id, но что-то пока лень, да и правильно ли это,
    // ведь теоретически это должно быть сделано уровнем выше, откуда мы и вызываем эту функцию,
    // кроме того, это как заголовок и не нужно давать это изменять
    span.innerHTML = name;

    f1.id = 'f' + id;
    span.id = 'span' + id;
    ul.id = 'ul' + id;

    // Показываем
    document.getElementById('tree_box').parentElement.style.zIndex = 9999;
    document.getElementById('tree_box').style.zIndex = 9999;
    document.getElementById('tree_box').hidden = false;
    document.getElementById('tree_box').parentElement.hidden = false;

    draw_folder('ul' + id);
}


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

    ul = document.getElementById(ul_id);

    // Очищаем элемент к которому будем цеплять веточку
    while (ul.childElementCount > 0) {
        ul.childNodes[0].remove();
    }

    for (let i = 0; i < data.length; i++) {

        let li_new = document.createElement('li');

        let span_new = document.createElement('span');
        span_new.className = "li";
        span_new.tabIndex = 20 + tree_next_tabindex++;
        span_new.innerHTML = data[i].name;
        span_new.onfocus = remember_me;

        li_new.append(span_new);
        ul.append(li_new);

        let flag = data[i].flags;
        if ((flag & 1) === 1) { // Это пункт
            li_new.id = 'i'+data[i].id;
            li_new.className = "item";
        } else {                // Это папка
            li_new.id = 'f'+data[i].id;
            li_new.className = "folder_closed";

            // К новой папке цепляем новыый элемент ul
            let ul_new = document.createElement('ul');
            ul_new.id = 'ul'+data[i].id;
            li_new.append(ul_new);
        }
    }
    ul.parentElement.childNodes[0].focus();
    ul.parentElement.className = "folder_opened";
}

//
//  При отжатии клавиши
//
//        top.onkeyup = li_onkeyup;
document.getElementById("tree_box").onkeyup = li_onkeyup;
function li_onkeyup(e) {

    let el = event.target;         // Элемент из которого вызываем меню SPAN
    let element = el.parentElement;    // LI

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
        case 'Delete':
            delete_node(element);
            break;
        case 'Enter':
            return_node_id(true);
            break;
        case 'Escape':
            return_node_id(false);
//                    alert(element.tabIndex + ',' + element.tagName+ ',' + element.id);
//                    alert(el.id);
//                     if (el.id !== 'btnAppendItem') {
//                         alert('ret');
//                         return_node_id(false);
//                     } else {
//                         let span1 = document.getElementById("span1").focus();
//                         alert(span1.id);
//                         event.stopPropagation();
//                     }
            break;
        case 'Tab':
//                    alert(el.tabIndex + ',' + el.tagName+ ',' + el.id);
            break;
        // default:
        //     alert(e.code);
    }

//        alert(element.nodeType + ', ' +element.nodeName + ', ' + element.id + ', ' + element.tagName + '=' + e.code);
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
// Рисуем веточку дерева
//

// При нажатии стрелки вправо
function expand_folder(el)
{
    // Если это папка
    if (el.childElementCount > 1) {
        draw_folder(el.childNodes[1].id);
    } else {
        el.childNodes[0].focus(); // Возвращаю фокус на узел
    }
}

//
// Спрятать веточку дерева при нажатии стрелки влево
//
function hide_folder(el)
{
    // Если это не папка
    if (el.childElementCount <= 1) {
        el.childNodes[0].focus(); // Возвращаю фокус на узел
        return;
    }

    let ul = el.childNodes[1];
    while (ul.childElementCount > 0) {
        ul.childNodes[0].remove();
    }
    el.className = "folder_closed";
    el.childNodes[0].focus();
}

// Раскрыть/свернуть веточку по правому клику (раньше было по двойному, название функции осталось)
// Кликаем либо на SPAN, либо на IL
function dblclick_expand_folder()
{
    let li = event.target;    // Пункт всплывающиего меню

    if (li.tagName === 'SPAN') {
        li = li.parentElement;
    }

    // Если это папка
    if (li.childElementCount > 1) {
        // и она развернута, то свернуть
        if (li.childNodes[1].childElementCount > 0) {
            hide_folder(li);
        } else {
            draw_folder(li.childNodes[1].id);
        }
    }
}

//
// Добавление пункта или папки
//
function append_node(li, is_folder)
{
    if (li.childElementCount < 2) {
        // Пытаемся добавить к пункту (li это не папка)
        return;
    }

    let new_id = 0;

    let flags = 1; // Признак Пункта в БД
    let msg = 'нового пункта';
    if (is_folder) {
        flags = 2; // Признак Папки в БД
        msg = 'новой папки';
    }

    let new_name = prompt('Добавление ' + msg + ', введите название');
    if(!new_name) { // Нажали отмену
        alert ('Отменили');
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
    span_new.tabIndex = 20 + tree_next_tabindex++;
    span_new.innerHTML = new_name;

    li_new.append(span_new);

    // Прицепляем новый элемент к папке. Это папка, так как вызов этой функции возможен только из папки
    // li.ul.append()
    li.childNodes[1].append(li_new);

    // Настройка нового пункта или папки
    if (is_folder) {
        li_new.id = 'f'+new_id;
        li_new.className = "folder_closed";

        // К новой папке цепляем новыый элемент ul
        let ul_new = document.createElement('ul');
        ul_new.id = 'ul'+new_id;
        li_new.append(ul_new);
    } else {
        li_new.id = 'i'+new_id;
        li_new.className = "item";
    }

    span_new.focus();
}

//
// Удаление пункта или папки
//
function delete_node(li)
{
    if (li.parentElement.id === 'root') {
        alert ('Нельзя удалять корневой элемент.');
        return;
    }

    if (!confirm('Удалить узел ' + li.childNodes[0].innerHTML + ' ?')) {
        alert ('Отменили');
        return;
    }

    move_up(li);

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
function rename_node(el) {

    if (el.parentElement.id === 'root') {
        alert ('Нельзя переименовывать корневой элемент.');
        return;
    }

    span = el.childNodes[0];

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
    span.focus();
}
