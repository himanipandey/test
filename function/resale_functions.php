<?php

    function checkEmail($check) {
        $expression = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$/";
        if (preg_match($expression, $check)) {
            return true;
        } else {
            return false;
        } 
    } 
    function checkBrokerByName($brokerName){
        
        $qry = "SELECT * FROM ".BROKER_LIST." WHERE BROKER_NAME = '".$brokerName."'";
        $res = mysql_query($qry) or die(mysql_error()." error in broker fectch by name");
        $arrBrokerByName = array();
        if(mysql_num_rows($res)>0)
            $arrBrokerByName[] = mysql_fetch_assoc($res);
        return $arrBrokerByName;
    }
    function getBrokerDetailById($brokerId){
        $qry = "SELECT * FROM ".BROKER_LIST." WHERE BROKER_ID = '".$brokerId."'";
        $res = mysql_query($qry) or die(mysql_error()." error in broker fectch");
        $arrBrokerByName = array();
        if(mysql_num_rows($res)>0){
            while($data = mysql_fetch_assoc($res)){
                    array_push($arrBrokerByName,$data);
            }
        }
        return $arrBrokerByName;     
    }
    function insertBroker($brokerName, $contactPerson, $address,$mobile,$email,$hq,$status){
        $qryIns =  "INSERT
                        INTO ".BROKER_LIST." 
                    SET
                        BROKER_NAME   = '".$brokerName."',
                        BROKER_ADDRESS= '".$address."',
                        CONTACT_NAME  = '".$contactPerson."',
                        BROKER_MOBILE = '".$mobile."',
                        BROKER_EMAIL  = '".$email."',
                        HQ            = '".$hq."',
                        STATUS        = '".$status."',
                        CREATION_DATE = now()";
        $resIns = mysql_query($qryIns) or die(mysql_error()." error in insert broker");
        if($resIns)
            return mysql_insert_id();
        else
            return false;
    }
    function updateBroker($brokerName, $contactPerson, $address,$mobile,$email,$hq,$status,$brokerId){
        $qryUp =  "UPDATE ".BROKER_LIST." 
                    SET
                        BROKER_NAME   = '".$brokerName."',
                        BROKER_ADDRESS= '".$address."',
                        CONTACT_NAME  = '".$contactPerson."',
                        BROKER_MOBILE = '".$mobile."',
                        BROKER_EMAIL  = '".$email."',
                        HQ            = '".$hq."',
                        STATUS        = '".$status."',
                        CREATION_DATE = now()
                    WHERE 
                        BROKER_ID = $brokerId";
        $resUp = mysql_query($qryUp) or die(mysql_error()." error in update broker");
        if($resUp)
            return true;
        else
            return false;
    }
    function getActiveBrokerList($cityId='', $broker='', $mobile=''){
        $city = '';
        $brokerName = '';
        $mobileNumber = '';
        if( $cityId !='' )
            $city = " AND HQ = $cityId";
        if( $broker != '' )
            $brokerName = " AND BROKER_NAME LIKE '%$broker%'";
        if( $mobile != '' ){
            $mobile = ltrim($mobile,"0");
            $mobileNumber = " AND BROKER_MOBILE LIKE '%$mobile%'";
        }
 
        $qry = "SELECT * FROM ".BROKER_LIST." WHERE STATUS = '1' $city $brokerName $mobileNumber ORDER BY BROKER_NAME ASC";
        $res = mysql_query($qry) or die(mysql_error()." error in active broker list");
        $arrActiveBrokerlist = array();
        if(mysql_num_rows($res)>0){
            while($data = mysql_fetch_assoc($res)){
                    array_push($arrActiveBrokerlist,$data);
            }
        }
        return $arrActiveBrokerlist;
    }
    function getBrokerByProject($projectId){
       $qry = "SELECT * FROM broker_project_mapping 
                WHERE PROJECT_ID = '".$projectId."'";
        $res = mysql_query($qry) or die(mysql_error()." error in  broker list by project id");
        $arrBrokerByProject = array();
        if(mysql_num_rows($res)>0){
            while($data = mysql_fetch_assoc($res)){
                    $arrBrokerByProject[$data['BROKER_ID']] = $data;
            }
        }
        return $arrBrokerByProject;
    }
    function deleteAllBrokerOfProject($projectId){
        $del = "DELETE FROM broker_project_mapping WHERE PROJECT_ID = '".$projectId."'";
        $res = mysql_query($del) or die(mysql_error());
        if($res)
            return true;
        else
            return false;
    }

    function getProjectByBroker($brokerId){
       $qry = "SELECT a.PROJECT_ID,a.BROKER_ID,b.PROJECT_NAME 
                FROM
                    broker_project_mapping a
                LEFT JOIN
                    ".RESI_PROJECT." b
                ON
                    a.PROJECT_ID = b.PROJECT_ID
                WHERE a.BROKER_ID = '".$brokerId."' and b.version = 'Cms'
                ORDER BY b.PROJECT_NAME ASC";
        $res = mysql_query($qry) or die(mysql_error()." error in  project list by broker id");
        $arrProjectByBroker = array();
        if(mysql_num_rows($res)>0){
            while($data = mysql_fetch_assoc($res)){
                    $arrProjectByBroker[] = $data;
            }
        }
        return $arrProjectByBroker;
    }
    
    function getBrokerPriceByProject($projectId, $brokerId = null, $effectiveDt = null){
        $effectiveDtQuery = '1 = 1';
        $brokerQuery = '1 = 1';

        if ($brokerId) {
            $brokerQuery = " BROKER_ID = '$brokerId'";
        }

        if ($effectiveDt) {
            $effectiveDtQuery = " EFFECTIVE_DATE = '$effectiveDt'";
        }

      $qry = "SELECT * 
                FROM 
                    project_secondary_price   
                WHERE
                    PROJECT_ID = $projectId
                AND $brokerQuery
                AND $effectiveDtQuery
                ORDER BY EFFECTIVE_DATE DESC, ID DESC";

        $res = mysql_query($qry) or die(mysql_error());
        $arrBrokerPriceByProject = array();

        while($data = mysql_fetch_assoc($res)){
            array_push($arrBrokerPriceByProject,$data);
        }

        return $arrBrokerPriceByProject;
    }
?>
