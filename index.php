<?php
include ('read_database.php');
?>
<html>
<head>
    <script src="jquery-3.1.1.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script type="text/JavaScript">


        function where() {
            $('#whereSelect').empty();
            $('#whereSelect').append("<option>Ogółem</option>");
            $('#whereSelect').append("<option>Miasto</option>");
            $('#whereSelect').append("<option>Wieś</option>");
        }


        function voivodeships() {
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
                    $.each(data, function (i) {
                        $('#voivodeshipSelect').append('<option value="'+ data[i].idWoj +'">'+ data[i].voivodeship +'</option>');
                    });
                },
                complete: function () {}
            });
        }
        function counties(vid) {
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
        function communities(cid) {
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
            where();
            voivodeships();
            $('#voivodeshipSelect').change(function () {
                $('#countySelect').empty();
                $('#communitySelect').empty();
                var voivodeships =  $('#voivodeshipSelect').val();
                $.each(voivodeships, function (i) {
                    counties(voivodeships[i]);

                });
            });
            $('#countySelect').change(function () {
                $('#communitySelect').empty();
                var counties =  $('#countySelect').val();
                $.each(counties, function (i) {
                    communities(counties[i]);

                });
            });
            $('#button').click(function () {
                var where = $('#whereSelect').val();
                var ifGeneral = 0, ifCities = 0, ifVillages = 0;
                if (jQuery.inArray("Ogółem", where) >= 0) {
                    ifGeneral = 1;
                }
                if (jQuery.inArray("Miasto", where) >= 0) {
                    ifCities = 1;
                }
                if (jQuery.inArray("Wieś", where) >= 0) {
                    ifVillages = 1;
                }

                var communities = $('#communitySelect').val();
                var counties = $('#countySelect').val();
                var voivodeships = $('#voivodeshipSelect').val();
                var names = [], generalPopulation = [], citiesPopulation = [], villagesPopulation = [];
                if (communities.length > 0) {
                    $.when($.ajax($.each(communities, function (i) {
                            $.ajax({
                                type: "POST",
                                url: "chart_data_communities.php?communities=" + communities[i],
                                contentType: "application/json; charset=utf-8",
                                success: function (selectedCommunity) {
                                  //  console.log(selectedCommunity);
                                    names.push(selectedCommunity[0].name);
                                    if (ifGeneral == 1) {
                                        generalPopulation.push(selectedCommunity[0].generalPopulation);
                                    }
                                    if (ifCities == 1) {
                                        citiesPopulation.push(selectedCommunity[0].citiesPopulation);
                                    }
                                    if (ifVillages == 1) {
                                        villagesPopulation.push(selectedCommunity[0].villagesPopulation);
                                    }
                                },
                                dataType: "json"
                            });
                        })).then(function () {
                            if (ifGeneral == 1 && ifVillages == 1 && ifCities == 1) {
                                showChartThreeSeries(generalPopulation, "Ogólna liczba ludności",
                                citiesPopulation, "Liczba ludności w miastach", villagesPopulation,
                                    "Liczba ludności na wsiach", names);
                            }
                            else if (ifGeneral == 1 && ifCities == 1) {
                                showChartTwoSeries(generalPopulation, "Ogólna liczba ludności",
                                    citiesPopulation, "Liczba ludności w miastach", names);
                            }
                            else if (ifVillages == 1 && ifCities == 1) {
                                showChartTwoSeries(citiesPopulation, "Liczba ludności w miastach", villagesPopulation,
                                    "Liczba ludności na wsiach", names);
                            }
                            else if (ifGeneral == 1 && ifVillages == 1) {
                                showChartTwoSeries(generalPopulation, "Ogólna liczba ludności",villagesPopulation,
                                    "Liczba ludności na wsiach", names);
                            }
                            else if (ifGeneral == 1) {
                                showChartOneSeries(generalPopulation, "Ogólna liczba ludności", names);
                            }
                            else if (ifCities == 1) {
                                showChartOneSeries(citiesPopulation, "Liczba ludności w miastach", names);
                            }
                            else if (ifVillages == 1) {
                                showChartOneSeries(villagesPopulation, "Liczba ludności we wsiach", names);
                            }
                        })
                    );
                 }
                 else if (counties.length > 0){
                    $.when($.ajax($.each(counties, function (i) {
                            $.ajax({
                                type: "POST",
                                url: "chart_data_counties.php?counties=" + counties[i],
                                contentType: "application/json; charset=utf-8",
                                success: function (selectedCounty) {
                                   // console.log(selectedCounty);
                                    names.push(selectedCounty[0].name);
                                    if (ifGeneral == 1) {
                                        generalPopulation.push(selectedCounty[0].generalPopulation);
                                    }
                                    if (ifCities == 1) {
                                        citiesPopulation.push(selectedCounty[0].citiesPopulation);
                                    }
                                    if (ifVillages == 1) {
                                        villagesPopulation.push(selectedCounty[0].villagesPopulation);
                                    }
                                },
                                dataType: "json"
                            });
                        })).then(function () {
                            console.log(generalPopulation);
                            console.log(villagesPopulation);
                            console.log(citiesPopulation);
                        if (ifGeneral == 1 && ifVillages == 1 && ifCities == 1) {
                            showChartThreeSeries(generalPopulation, "Ogólna liczba ludności",
                                citiesPopulation, "Liczba ludności w miastach", villagesPopulation,
                                "Liczba ludności na wsiach", names);
                        }
                        else if (ifGeneral == 1 && ifCities == 1) {
                            showChartTwoSeries(generalPopulation, "Ogólna liczba ludności",
                                citiesPopulation, "Liczba ludności w miastach", names);
                        }
                        else if (ifVillages == 1 && ifCities == 1) {
                            showChartTwoSeries(citiesPopulation, "Liczba ludności w miastach", villagesPopulation,
                                "Liczba ludności na wsiach", names);
                        }
                        else if (ifGeneral == 1 && ifVillages == 1) {
                            showChartTwoSeries(generalPopulation, "Ogólna liczba ludności",villagesPopulation,
                                "Liczba ludności na wsiach", names);
                        }
                            else if (ifGeneral == 1) {
                                showChartOneSeries(generalPopulation, "Ogólna liczba ludności", names);
                            }
                            else if (ifCities == 1) {
                                showChartOneSeries(citiesPopulation, "Liczba ludności w miastach", names);
                            }
                            else if (ifVillages == 1) {
                                showChartOneSeries(villagesPopulation, "Liczba ludności we wsiach", names);
                            }
                        })
                    );
                }
                else if (voivodeships.length > 0) {
                    $.when($.ajax($.each(voivodeships, function (i) {
                            $.ajax({
                                type: "POST",
                                url: "chart_data_voivodeships.php?voivodeship=" + voivodeships[i],
                                contentType: "application/json; charset=utf-8",
                                success: function (selectedVoivodeship) {
                                    names.push(selectedVoivodeship[0].name);
                                    if (ifGeneral == 1) {
                                        generalPopulation.push(selectedVoivodeship[0].generalPopulation);
                                    }
                                    if (ifCities == 1) {
                                        citiesPopulation.push(selectedVoivodeship[0].citiesPopulation);
                                    }
                                    if (ifVillages == 1) {
                                        villagesPopulation.push(selectedVoivodeship[0].villagesPopulation);
                                    }
                                },
                                dataType: "json"
                            });
                        })).then(function () {
                        if (ifGeneral == 1 && ifVillages == 1 && ifCities == 1) {
                            showChartThreeSeries(generalPopulation, "Ogólna liczba ludności",
                                citiesPopulation, "Liczba ludności w miastach", villagesPopulation,
                                "Liczba ludności na wsiach", names);
                        }
                        else if (ifGeneral == 1 && ifCities == 1) {
                            showChartTwoSeries(generalPopulation, "Ogólna liczba ludności",
                                citiesPopulation, "Liczba ludności w miastach", names);
                        }
                        else if (ifVillages == 1 && ifCities == 1) {
                            showChartTwoSeries(citiesPopulation, "Liczba ludności w miastach", villagesPopulation,
                                "Liczba ludności na wsiach", names);
                        }
                        else if (ifGeneral == 1 && ifVillages == 1) {
                            showChartTwoSeries(generalPopulation, "Ogólna liczba ludności",villagesPopulation,
                                "Liczba ludności na wsiach", names);
                        }
                            else if (ifGeneral == 1) {
                                showChartOneSeries(generalPopulation, "Ogólna liczba ludności", names);
                            }
                            else if (ifCities == 1) {
                                showChartOneSeries(citiesPopulation, "Liczba ludności w miastach", names);
                            }
                            else if (ifVillages == 1) {
                                showChartOneSeries(villagesPopulation, "Liczba ludności we wsiach", names);
                            }
                        })
                    );
                }
            });

        });

    </script>
</head>
<body>
    <span>Miejsce zamieszkania</span>
    <select multiple="multiple" id="whereSelect"></select>
    <span>Województwo</span>
    <select multiple="multiple" id="voivodeshipSelect" ></select>
    <span>Powiat</span>
    <select multiple="multiple" id="countySelect"></select>
    <span>Gmina</span>
    <select multiple="multiple" id="communitySelect"></select>
    <button id="button" type="button">Wybierz</button>
    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

    <script>
        function  showChartOneSeries(population, seriesName, names) {
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
                    categories: names,
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
                    name: seriesName,
                    dataType: "json",
                    data: population

                }]
            });
        }
        function showChartTwoSeries(population1, name1, population2, name2, names) {
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
                    categories: names,
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
                    name: name1,
                    dataType: "json",
                    data: population1

                }, {
                    name: name2,
                    dataType: "json",
                    data: population2
                }]
            });
        }

        function showChartThreeSeries(population1, name1, population2, name2, population3, name3, names) {
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
                    categories: names,
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
                    name: name1,
                    dataType: "json",
                    data: population1

                }, {
                    name: name2,
                    dataType: "json",
                    data: population2
                }, {
                    name: name3,
                    dataType: "json",
                    data: population3
                }]
            });
        }
    </script>


</body>
</html>