<?php
include ('read_database.php');
?>
<head>
    <script src="jquery-3.1.1.min.js"></script>
    <script type="text/JavaScript">
        //TODO zmienić id opcji na id w bazie (powtarzające się nazwy powiatów (i być może gmin))
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
                        $('#voivodeshipSelect').append('<option value="'+ data[i].idWoj +'">'+ data[i].voivodeship +'</option>');
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
                        $('#countySelect').append('<option value="'+ data[i].idPow+'">'+ data[i].county +'</option>');
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
                    console.log(data.length);
                    $.each(data, function (i) {
                        $('#communitySelect').append('<option value="'+ data[i].idCom +'">'+ data[i].community +'</option>');
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
                    Counties(voivodeships[i]);

                });
            });
            $('#countySelect').change(function () {
                $('#communitySelect').empty();
                var counties =  $('#countySelect').val();
                $.each(counties, function (i) {
                    Communities(counties[i]);

                });
            });

        });

    </script>
</head>
<body>
    <span>Województwo</span>
    <select multiple="multiple" id="voivodeshipSelect" ></select>
    <span>Powiat</span>
    <select multiple="multiple" id="countySelect"></select>
    <span>Gmina</span>
    <select multiple="multiple" id="communitySelect"></select>
</body>
</html>