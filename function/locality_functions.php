<?php

/* Created a URL given a label of locality */
function createLocalityURL($localityLabel, $cityLabel, $id) {
    $localityLabel = trim(strtolower($localityLabel));
    $cityLabel = trim(strtolower($cityLabel));
    $cleanLocalityLabel = preg_replace( '/\s+/', '-', $localityLabel);
    return $cityLabel."/"."property-sale-".$localityLabel.'-'.$id;
}

function createProjectURL($city, $locality, $builderName, $projectName, $projectId){
    $city = trim(strtolower($city));
    $locality = trim(strtolower($locality));
    $builder = trim(strtolower($builderName));
    $project = trim(strtolower($projectName));
    $projectURL = $city.'/'.$locality.'/'.$builder.'-'.$project.'-'.$projectId;
    return preg_replace( '/\s+/', '-', $projectURL);
}

function createBuilderURL($builderName, $builderId){
    $builder = trim(strtolower($builderName));
    $builderURL = $builder.'-'.$builderId;
    return preg_replace( '/\s+/', '-', $builderURL);
}   

?>
