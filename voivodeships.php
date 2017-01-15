<?php
    include 'read_database.php';
    $voivodehips = getAllVoivedeships($db);
    $data = array();
    while ($x = $voivodehips->fetchArray()) {
        $data[] = array(
                 'voivodeship' => $x['Województwo'],
                 'idWoj' => $x['IDWoj']
         );
    }
    header('Content-type: application/json');
    echo json_encode($data);
?>

