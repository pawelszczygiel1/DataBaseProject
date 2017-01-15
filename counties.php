<?php
    include 'read_database.php';
    $counties = getCounties($_GET['vid'], $db);
    $data = array();
    while ($x = $counties->fetchArray()) {
        $data[] = array(
            'county' => $x['Powiat']
        );
    }
    header('Content-type: application/json');
    echo json_encode($data);
?>