Редактирование дневника
здесь надо расположить список записей и форму редактирования записи

Можно расположить список в левой колонке, но этот вариант мне не нравится.

Можно сделать табличную разметку.

Можно дивами. <br>
<div id="info" class="red">
    info
</div>



<div class="grid-container-edit">

    <aside class="edit-list">
        Список
        <div id="list-id" class="div-list">
        списокwwwwwwwwwwwwwwwwwwww<br>список<br>список<br>писок<br>список<br>писок<br>список<br>список<br>список<br>список2<br>список3<br>список4<br>список5<br>список6<br>список7<br>список8<br>писок9<br>список11<br>
        список1<br>список2<br>список3<br>список<br>список<br>список<br>список<br>писок<br>список<br>список<br>список<br>
        список1<br>список2<br>список3<br>список<br>список<br>список<br>список<br>писок<br>список<br>список<br>список<br>
        </div>
    </aside>

    <aside class="edit-element">
        Элемент списка
        <div id="element-id" class="div-element">
            списокwwwwwwwwwwwwwwwwwwww<br>список<br>список<br>писок<br>список<br>
        </div>
    </aside>

<button onclick="wd()">left</button>

</div>


<script>

    function wd()
    {
        let w = $("#list-id").width();
//        alert(w);
        w = w + 3;
//        alert(w);
        $("#list-id").height(w);
        $("#list-id").width(w);
    }


    ar = ajax_render('mdn/mdnDaysList.php', ['Hi']);
    //    $("#output").text(ar);
    document.getElementById("list-id").innerHTML=ar;

    ar = ajax_render('mdn/mdnDayElement.php', ['Hi']);
    $("#element-id").text(ar);

    /*
     * Подогнать высоту списка к высоте формы элемента списка
     */
    function set_height()
    {
        let h = $("#element-id").height();

        $("#info").text("Высота элемента = "+h)

        if (h < 300) h = 300;

        $("#list-id").height(h);
    }

    set_height();

</script>


<?php
