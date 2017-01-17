<?php
include ('read_database.php');
?>
<html>
<head>
    <script src="jquery-3.1.1.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
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
                $('#communitySelect').empty();
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
            $('#button').click( function () {
                //TODO tu chcę przesłać wybrane gminy do pliku chart_data.php, który wykonuje zapytanie do bazy i potem odebrać zmienną od niego
                var communities = $('#communitySelect').val();
                $.post('chart_data.php', {communities : communities});
                $.ajax({
                    type: "POST",
                    url: "chart_data.php",
                    dataType: "json",
                    success: function (data) {
                        showChart(data);
                    },
                    complete: function () {
                    }
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
    <button id="button" type="button">Wybierz</button>
    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

    <script>
        function  showChart(data) {
            Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'liczba ludności'
                },
                subtitle: {
                    text: 'źródło danepubliczne.gov.pl'
                },
                xAxis: {
                    categories: [],//nazwy kolejnych gmin
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Ludność'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Tokyo',
                    dataType: "json",
                    data: data//"chart_data.php"

                }]
            });
        }
    </script>


</body>
</html>