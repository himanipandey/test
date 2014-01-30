<?php
    include("dbConfig.php"); 
    $status = array(
        '0' => 'No Action Taken',
        '1' => 'No issue Found',
        '2' => 'Issue found / being resolved',
        '3' => 'Error Corrected',
    );
    $errorDataArr = array();    
    $QueryMember = "SELECT b.* FROM proptiger.RESI_PROJECT_ERROR a INNER JOIN proptiger.RESI_PROJECT_ERROR_DETAILS b ON a.ID = b.ERROR_ID WHERE a.ID = '".$_GET['errid']."' ORDER BY b.ID DESC";
    $QueryExecute = mysql_query($QueryMember) or die(mysql_error());
    $NumRows 	  = mysql_num_rows($QueryExecute);
    $QueryExecute_1 = mysql_query($QueryMember);
?>
<div>Error-Id: <?php echo $_GET['errid'];?></div>
<table cellspacing="1" cellpadding="2" border="1" width="100%">
    <tr>
        <th>Status</th>
        <th>Comment</th>
        <th>Date Modified</th>
    </tr>
    <?php 
    if($NumRows >0){
        while ($dataArr2 = mysql_fetch_assoc($QueryExecute_1)){	
            ?>
                <tr>
                    <td><?php echo $status[$dataArr2['STATUS_ID']];?></td>
                    <td><?php echo $dataArr2['COMMENTS'];?></td>
                    <td><?php echo date('d-m-Y h:i:s',  strtotime($dataArr2['DATE']));?></td>
                </tr>
             <?php
        }
    }else{
        echo "<tr>
                    <td colspan='3'>No History for this Error</td>        
                </tr>
        ";
    }
    ?>
</table>

<?php

   
?>
