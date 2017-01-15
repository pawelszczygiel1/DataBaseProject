<?php
    include 'read_database.php';
    $voivodehips = getAllVoivedeships($db);
    $data = array();
    while ($x = $voivodehips->fetchArray()) {
        echo $x['Województwo'];
        $data[] = array(
                 'id' => $x['Województwo'],
                 'voivodeship' => $x['Województwo']
         );
    }
    header('Content-type: application/json');
    echo json_encode($data);
?>

