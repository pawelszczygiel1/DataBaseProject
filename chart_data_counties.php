<?php
    include 'read_database.php';
    $county = json_decode($_GET['counties']);
    $selectedCounties = array();
    $data = getCountyPopulation($county, $db);
    $x = $data->fetchArray();
    $selectedCounty[] = array(
        'name' => $x['Powiat'],
        'generalPopulation' => $x['LudnośćMiasto'] + $x['LudnośćWieś'],
        'villagesPopulation' => $x['LudnośćWieś'],
        'citiesPopulation' => $x['LudnośćMiasto']
    );
    header('Content-type: application/json');
    echo json_encode($selectedCounty);

?>