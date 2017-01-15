<?php
include 'read_database.php';
?>
<html>
<head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script type="text/JavaScript">
        function Voivodeships() {
            $('#voivodeshipSelect').empty();
            $('#voivodeshipSelect').append("<option>Ładowanie....</option>");
            $('#countySelectSelect').append("<option>Wybierz powiat....</option>");
            $.ajax({
                type: "POST",
                url: "voivodeships.php";
                contentType:"application/json",
                dataType:"json",
                success:function (data) {
                $('#voivodeshipSelect').empty();
                $('#voivodeshipSelect').append("<option value='0'>Wybierz województwo</option>");
                $.each(data, function (i, item) {
                    $('#voivodeshipSelect').append('<option value="' + data[i].id + '">' + data[i].voivodeship + '</option>');
                });
            },
            complete: function () {}
            });
        }
        function Counties(vid) {
            $('#countySelect').empty();
            $('#countySelect').append("<option>Ładowanie....</option>");
            $.ajax({
                type: "POST",
                url: "countes.php?vid="+vid,
                contentType:"application/json; charset utf-8",
                dataType:"json",
                success:function (data) {
                    $('#countySelect').empty();
                    $('#countySelect').append("<option value='0'>Wybierz powiat</option>");
                    $.each(data, function (i, item) {
                        $('#countySelect').append('<option value="' + data[i].id + '">' + data[i].county + '</option>');
                    });
                },
                complete: function () {}
        });
        }
        $(document).ready(function () {
            print("początek");
            Voivodeships();
            $('#voivodeshipSelect').change(function () {
                var voivodeshipId =  $('#voivodeshipSelect').val();
                Counties(voivodeshipId);
            });
        });

    </script>
</head>
<body>
    <span>Województwo</span>
    <select id="voivodeshipSelect"></select>
    <span>Powiat</span>
    <select id="countySelect"></select>
</body>
</html>