<?php
    include 'read_database.php';
    if (isset($_POST['communities'])) {
                $communities = json_decode($_POST['communities']);
                $selectedCommunities = array();
                foreach ($communities as $community) {
                    $population = getCommunityPopulation($community, $db);
                    $population = $population->fetchArray();
                    $selectedCommunities = array(
                        'population' => $population['LudnośćMiasto'] + $population['LudnośćWieś'],
                        'name' => $population['Gmina'],
                        'id' => $population['IDGmina']
                    );
                }
                echo json_encode($selectedCommunities);
    }
?>