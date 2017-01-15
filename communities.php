<?php
    include 'read_database.php';
    $communities = getCommunities($_GET['cid'], $db);
    $data = array();
    while ($x = $communities->fetchArray()) {
        $data[] = array(
            'community' => $x['Gmina'],
            'idCom' => $x['IDGmina']
        );
    }
    header('Content-type: application/json');
    echo json_encode($data);
?>