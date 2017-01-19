<?php
    include 'read_database.php';
    $county = json_decode($_GET['counties']);
    $selectedCounties = array();
    $data = getCountyPopulation($county, $db);
    $x = $data->fetchArray();
    $selectedCounty[] = array(
        'population' => $x['Population'],
        'name' => $x['Powiat']
    );
    header('Content-type: application/json');
    echo json_encode($selectedCounty);

?>