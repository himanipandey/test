<?php

/* Created a URL given a label of locality */
function createLocalityURL($localityLabel, $cityLabel) {
    $localityLabel = strtolower($localityLabel);
    $cityLabel = strtolower($cityLabel);
    str_replace(' ', '-', $localityLabel);
    return "property-in-$localityLabel-$cityLabel-real-estate.php";
}

?>