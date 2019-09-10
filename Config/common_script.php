<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="excel/dist/jquery.table2excel.js"></script>
<script>
    $(document).ready(function () {


        $("#myTable tr td div").css({
            "height": "50px",
            "overflow": "hidden"
        });
        $("#myTable tr td div").dblclick(function () {
            $("#myTable tr td div").css({
                "height": "50px",
                "overflow": "hidden"
            });

            $(this).css({
                "height": "auto",
                "overflow": "visible"
            });
        });
        var doubleclickeditem;
        $("#myTable tr td").dblclick(function () {
            $("#myTable tr td").removeAttr("contenteditable");
            $("#myTable tr td").css({"border": "none"})
            $(this).attr("contenteditable", 'true');
            $(this).css({"border": "thin solid red"})
            doubleclickeditem = $(this);
        });

        $(".printExcel").click(function () {
            $(function () {
                $("#myTable").table2excel({
                    exclude: ".noExl",
                    name: "Excel Document Name",
                    filename: "myFileName" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true
                });
            });
        });
    });
    function clickTo(path) {
        window.location.href = "" + path;
    }
</script>