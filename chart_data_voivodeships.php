<?php
    include 'read_database.php';
    $voivodeship = json_decode($_GET['voivodeship']);
    $selectedVoivodeship = array();
    $data = getVoivodeshipPopulation($voivodeship, $db);
    $x = $data->fetchArray();
    $selectedVoivodeship[] = array(
        'population' => $x['Population'],
        'name' => $x['Województwo']
    );
    header('Content-type: application/json');
    echo json_encode($selectedVoivodeship);

?>