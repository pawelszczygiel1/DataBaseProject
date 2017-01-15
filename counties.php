<?php
    include 'read_database.php';
    $voivodehips = selectAllVoivodeship($_GET["vid"], $db);
    $data = array();
    while ($x = $voivodehips->fetchArray()) {
        $data[] = array(
            'id' => $x['Powiat'],
            'vid' => $x['Województwo'],
            'county' => $x['Powiat']
        );
    }
    header('Content-type: application/json');
    echo json_encode($data);
?>