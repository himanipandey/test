<?php

    $connection = ssh2_connect('10.0.0.26', 22);
    ssh2_auth_password($connection, 'sysadmin', '');
    ssh2_exec($connection, "ls /home/sysadmin/production/; php testContentData.php;");
    echo "<br><br>last line";
    //ssh2_exec($connection, "cd  /home/sysadmin/production/solr; php solrIndex.php $projectId");
//$msg = "Successfully migrated following ProjectIds:<br>$projectId";
?>
