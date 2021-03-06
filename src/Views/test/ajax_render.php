<h2>ajax_render_test</h2>

<div id="output">mmm</div>

<script>


    ar = 'zxcv';
    ar = ajax_render('test/ajax_page.php', ['Hi']);
//    ar = ajax_render('test/ajax_page.php', 'hi');

//    $("#output").text(ar);
    document.getElementById("output").innerHTML=ar;

//    alert(ar);

    // function ajax_render(filename, params)
    // {
    //     $.ajaxSetup({async:false});
    //
    //     ar_result = '';
    //     $.post("/ajax/render_file",
    //         {
    //             filename: filename,
    //             params: params
    //         },
    //             function (data, status) {
    //             ar_result = data;
    //         }
    //     );
    //
    //     return ar_result;
    // }

    function oc() {
        alert("Hi, <?= ' world!'; ?>");
//        alert("Hi");
    }

//    oc();

</script>

<?= 'цццццццццццццццццц!'; ?>