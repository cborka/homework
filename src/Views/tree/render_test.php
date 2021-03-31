<div id="rentest">

    <div id="txt_test">Как дела?</div>


    <button onclick="fun_test()">TEST</button>


</div>


<script>

    fun_test();
    function fun_test() {
        let t = document.getElementById("txt_test");
        t.innerHTML = 'Нажата кнопка';
    }


</script>