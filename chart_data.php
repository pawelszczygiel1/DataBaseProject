<?php
    include 'read_database.php';
    $community = json_decode($_GET['communities']);
    $selectedCommunities = array();
        $data = getCommunityPopulation($community, $db);
        $x = $data->fetchArray();
        $selectedCommunity[] = array(
            'population' => $x['LudnośćMiasto'] + $x['LudnośćWieś'],
            'name' => $x['Gmina'],
            'id' => $x['IDGmina']
        );
    header('Content-type: application/json');
    echo json_encode($selectedCommunity);

?>