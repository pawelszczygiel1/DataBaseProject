<?php
    include 'read_database.php';
    $voivodeship = json_decode($_GET['voivodeship']);
    $selectedVoivodeship = array();
    $data = getVoivodeshipPopulation($voivodeship, $db);
    $x = $data->fetchArray();
    $selectedVoivodeship[] = array(
        'name' => $x['Województwo'],
        'generalPopulation' => $x['LudnośćMiasto'] + $x['LudnośćWieś'],
        'villagesPopulation' => $x['LudnośćWieś'],
        'citiesPopulation' => $x['LudnośćMiasto']
    );

    header('Content-type: application/json');
    echo json_encode($selectedVoivodeship);

?>