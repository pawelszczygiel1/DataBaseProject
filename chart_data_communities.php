<?php
    include 'read_database.php';
    $community = json_decode($_GET['communities']);
    $selectedCommunities = array();
    $data = getCommunityPopulation($community, $db);
    $x = $data->fetchArray();

    $selectedCommunity[] = array(
        'name' => $x['Gmina'],
        'id' => $x['IDGmina'],
        'generalPopulation' => $x['LudnośćMiasto'] + $x['LudnośćWieś'],
        'villagesPopulation' => $x['LudnośćWieś'],
        'citiesPopulation' => $x['LudnośćMiasto']
    );
    header('Content-type: application/json');
    echo json_encode($selectedCommunity);

?>