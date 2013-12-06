<?php
class Supply {

  private $DBCRM;

  function __construct( $conn ) {
    $this->DBCRM = $conn;
  }

  public function AddBroker( $name, $contact, $email, $mobile, $address ) {
    $name = mysql_escape_string( $name );
    $contact = mysql_escape_string( $contact );
    $address = mysql_escape_string( $address );
    $email = mysql_escape_string( $email );
    $insertQuery = "INSERT INTO BROKER_LIST ( BROKER_NAME, CONTACT_NAME, BROKER_EMAIL, BROKER_MOBILE, BROKER_ADDRESS, CREATION_DATE ) VALUES ( '$name', '$contact', '$email', '$mobile', '$address', NOW() )";
    return $this->DBCRM->Insert( $insertQuery );
  }

  public function GetBrokerList( $unsorted = false ) {
    if ( $unsorted ) {
      $query = "SELECT * FROM BROKER_LIST";
    }
    else {
      $query = "SELECT * FROM BROKER_LIST ORDER BY BROKER_NAME";
    }
    $result = $this->DBCRM->Query( $query );
    return $result;
  }

  public function GetBrokerById( $id ) {
    if ( is_numeric( $id ) && $id > 0 ) {
      $query = "SELECT * FROM BROKER_LIST WHERE BROKER_ID = $id";
      $result = $this->DBCRM->Row( $query );
      return $result;
    }
    else {
      return false;
    }
  }

  public function GetInventory( $inventoryId ) {
    if ( $inventoryId > 0 ) {
      $query = "SELECT * FROM RESALE_INVENTORY WHERE ID = $inventoryId";
      $result = $this->DBCRM->Row( $query );
      return $result;
    }
  }

  public function GetInventoryForMail( $inventoryId ) {
    if ( $inventoryId > 0 ) {
      $query = "SELECT
                '' AS 'Added By',
                ID AS 'Resale Listing Id',
                DATE(CREATION_DATE) AS 'Created Date',
                IF( CONTACT_TYPE = 0, 'OWNER', 'BROKER' ) AS 'Contact Person Type',
                '' AS 'Builder Name',
                PROJECT_NAME AS 'Project Name',
                '' AS 'Locality',
                '' AS 'Available Property',
                INDICATIVE_PRICE AS 'Indicative Price'
            FROM RESALE_INVENTORY WHERE ID = $inventoryId";
      $result = $this->DBCRM->Row( $query );
      return $result;
    }
  }

  public function AddInventoryToDB( $inputParam ) {
    if ( !empty( $inputParam ) && isset( $_SESSION['adminId'] ) ) {
      $query = "INSERT INTO RESALE_INVENTORY (
                ADDED_BY,
                CONTACT_TYPE,
                CONTACT_NAME,
                CONTACT_EMAIL,
                CONTACT_MOBILE,
                LOGIN_RATE,
                LOGIN_RATE_UNIT,
                DEMAND_RATE,
                DEMAND_RATE_UNIT,
                OTHER_CHARGE,
                OTHER_CHARGE_UNIT,
                PARKING_CHARGE,
                AMOUNT_PAID,
                AMOUNT_PAID_UNIT,
                INDICATIVE_PRICE,
                ON_HOME_LOAN,
                IS_NEGOTIABLE,
                REMARK,
                PROJECT_ID,
                PROJECT_NAME,
                FLAT_NO,
                FLOOR_NO,
                ADDRESS,
                DESCRIPTION,
                PROPERTY_OPTION_ID,
                TOWER_ID,
                CREATION_DATE
            ) VALUES (
                '".$_SESSION['adminId']."',
                '".$inputParam['CONTACT_TYPE']."',
                '".mysql_escape_string( $inputParam['CONTACT_NAME'] )."',
                '".mysql_escape_string( $inputParam['CONTACT_EMAIL'] )."',
                '".$inputParam['CONTACT_MOBILE']."',
                '".$inputParam['LOGIN_RATE']."',
                '".$inputParam['LOGIN_RATE_UNIT']."',
                '".$inputParam['DEMAND_RATE']."',
                '".$inputParam['DEMAND_RATE_UNIT']."',
                '".$inputParam['OTHER_CHARGE']."',
                '".$inputParam['OTHER_CHARGE_UNIT']."',
                '".$inputParam['PARKING_CHARGE']."',
                '".$inputParam['AMOUNT_PAID']."',
                '".$inputParam['AMOUNT_PAID_UNIT']."',
                '".$inputParam['INDICATIVE_PRICE']."',
                '".$inputParam['ON_HOME_LOAN']."',
                '".$inputParam['IS_NEGOTIABLE']."',
                '".mysql_escape_string( $inputParam['REMARK'] )."',
                '".$inputParam['PROJECT_ID']."',
                '".$inputParam['PROJECT_NAME']."',
                '".mysql_escape_string( $inputParam['FLAT_NO'] )."',
                '".$inputParam['FLOOR_NO']."',
                '".mysql_escape_string( $inputParam['ADDRESS'] )."',
                '".mysql_escape_string( $inputParam['DESCRIPTION'] )."',
                '".$inputParam['PROPERTY_OPTION_ID']."',
                '".$inputParam['TOWER_ID']."',
                NOW())";

      return $this->DBCRM->Insert( $query );
    }
    return -1;
  }

  function getSearchResultListings($projects,$params) {

    $flag=0;
    $flag1=1;
    $query = "SELECT * FROM RESALE_INVENTORY";

    if($params['BROKER_NAME']!=-1 && $params['BROKER_NAME']!='') {
      $flag1=0;
      $query1= "SELECT * FROM BROKER_LIST WHERE BROKER_ID = ".$params['BROKER_NAME'];
      $result1 = $this->DBCRM->Query($query1);
    }

    if($projects[0]['PROJECT_ID']!='-1'){
      $flag=1;
      $query.=" WHERE PROJECT_ID IN (".implode(",", $projects).")";
    }

    if($params['CONTACT_TYPE']==0){
      if($flag==0){
	$query.=" WHERE";
	$flag=1;
      }
      else
	$query.=" AND";
      $query.=" CONTACT_TYPE = 0";
    }
    else if($params['CONTACT_TYPE']>0){
      if($flag==0){
	$query.=" WHERE";
	$flag=1;
      }
      else
	$query.=" AND";
      $query.=" CONTACT_TYPE > 0";
    }

    if($params['PROJECT_NAME']!=-1 && $params['PROJECT_NAME']!=''){
      if($flag==0){
	$query.=" WHERE";
	$flag=1;
      }
      else
	$query.=" AND";
      $query.=" PROJECT_NAME LIKE '%".$params['PROJECT_NAME']."%'";
    }

    if($params['ID']!=-1 && $params['ID']!=''){
      if($flag==0){
	$query.=" WHERE";
	$flag=1;
      }
      else
	$query.=" AND";

      $query.=" ID = ".$params['ID'];
    }

    if($params['Status']!=-1 && $params['Status']!=''){
      if($flag==0){
	$query.=" WHERE";
	$flag=1;
      }
      else
	$query.=" AND";

      $query.=" STATUS = ".$params['Status'];
    }

    $result = $this->DBCRM->Query( $query );

    if(!empty($result))
      foreach ($result as $key => $value) {
	if($value['CONTACT_TYPE']!=0 && $value['CONTACT_TYPE']!='0') {
	  $res=$this->GetBrokerById($value['CONTACT_TYPE']);
	  if($res){
	    $result[$key]['BROKER_NAME']=$res['BROKER_NAME'];
	    $result[$key]['CONTACT_NAME']="";
	    $result[$key]['CONTACT_EMAIL']=$res['BROKER_EMAIL'];
	    $result[$key]['CONTACT_MOBILE']=$res['BROKER_MOBILE'];
	  }
	}
	else
	  $result[$key]['BROKER_NAME']="";
      }
    if($flag1==0) {
      foreach ($result as $key => $value) {
	if($result1[0]['BROKER_ID']!=$result[$key]['CONTACT_TYPE'])
	  unset($result[$key]);
      }
    }

    return $result;
  }
  
  function getListingByID($id) {
    $query = "SELECT * FROM RESALE_INVENTORY WHERE ID = $id";
    $result = $this->DBCRM->Query( $query );
    return $result;
  }

  function updateRecord($params) {
    $query='UPDATE `RESALE_INVENTORY` SET ';
    foreach ($params as $key => $value) {
      if($key!='ID' && $key!='action' && $key!='submit' && $key!='CHANGED_STATUS_DATE')
	$query.=' `'.$key.'` = "'.$value.'",';
      if($key=='CHANGE_STATUS_DATE')
	$query.=' `'.$key.'` = '.$value.',';
    }
    $query=rtrim($query,",");
    $query.=' WHERE ID='.$params['ID'];
    $result = $this->DBCRM->Query( $query );
    return $result;
  }

  function AddAdditionalDetails( $optionId, $param ) {
    if ( empty( $param ) ) {
      return;
    }
    $set = array();
    foreach( $param as $col => $val ) {
      $set[] = $col." = '".$val."'";
    }
    $set = implode(', ', $set);
    $query = "UPDATE RESALE_INVENTORY SET $set WHERE ID = $optionId";
    return $this->DBCRM->Execute( $query );
  }
  function getStatusByID($id) {
    $query = "SELECT STATUS FROM RESALE_INVENTORY WHERE ID = $id";
    $result = $this->DBCRM->Query( $query );
    return $result[0]['STATUS'];
  }
  function getUsers($id) {
    $query = "SELECT USERNAME,ADMINID FROM proptiger_admin WHERE ADMINID=$id";
    $result = $this->DBCRM->Query( $query );
    return $result;
  }
}

?>
