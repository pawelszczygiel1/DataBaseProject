<?php
include ('read_database.php');
?>
<html>
<head>
    <script src="jquery-3.1.1.min.js"></script>
    <script type="text/JavaScript">
        function Voivodeships() {
            $('#voivodeshipSelect').empty();
            $('#voivodeshipSelect').append("<option>Ładowanie....</option>");
            $('#countySelectSelect').append("<option>Wybierz powiat....</option>");
            $.ajax({
                type: "POST",
                url: "voivodeships.php",
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (data) {
                    $('#voivodeshipSelect').empty();
                    $('#voivodeshipSelect').append("<option value='0'>Wybierz województwo</option>");
                    $.each(data, function (i) {
                        $('#voivodeshipSelect').append('<option value="'+ data[i].voivodeship +'">'+ data[i].voivodeship +'</option>');
                    });
                },
                complete: function () {}
            });
        }
        function Counties(vid) {
            $.ajax({
                type: "POST",
                url: "counties.php?vid="+vid,
                contentType:"application/json; charset utf-8",
                dataType:"json",
                success:function (data) {

                    $.each(data, function (i) {
                        $('#countySelect').append('<option value="'+ data[i].county +'">'+ data[i].county +'</option>');
                    });
                },
                complete: function () {}
        });
        }
        function Communities(cid) {
            $.ajax({
                type: "POST",
                url: "communities.php?cid="+cid,
                contentType:"application/json; charset utf-8",
                dataType:"json",
                success:function (data) {

                    $.each(data, function (i) {
                        $('#communitySelect').append('<option value="'+ data[i].community +'">'+ data[i].community +'</option>');
                    });
                },
                complete: function () {}
            });
        }
        $(document).ready(function () {
            Voivodeships();
            $('#voivodeshipSelect').change(function () {
                $('#countySelect').empty();
                var voivodeships =  $('#voivodeshipSelect').val();
                $.each(voivodeships, function (i) {
                    console.log(voivodeships[i]);
                    Counties(voivodeships[i]);

                });
            });
        });

    </script>
</head>
<body>
    <span>Województwo</span>
    <select multiple="multiple" id="voivodeshipSelect"></select>
    <span>Powiat</span>
    <select multiple="multiple" id="countySelect"></select>
    <span>Gmina</span>
    <select multiple="multiple" id="communitySelect"></select>
</body>
</html>