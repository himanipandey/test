<?php
//error_reporting(1);
//ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	AdminAuthentication();
	
	if(isset($_REQUEST['dbName']) AND isset($_REQUEST['tblName']))
	{
		echo $dbName  = $_REQUEST['dbName'];
		echo $tblName = $_REQUEST['tblName'];
		echo "<br><br>";
		createTableStructure($tblName,$dbName);

	}
	else
	{
		echo "Please provide table name and DB name in url like:<br>
				http://cms.proptiger.com/admin_cms/project_add/generateAuditQueries.php?dbName={x}&tblName={y}";
	}
	
	
	/**********function for create audit table for triger use******************/
	function createTableStructure($tblName,$dbName)
	{
		$qry  = "SELECT COLUMN_NAME,COLUMN_DEFAULT,DATA_TYPE,NUMERIC_PRECISION,COLUMN_TYPE,COLUMN_DEFAULT,IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS
		WHERE
		table_name = '$tblName' AND  TABLE_SCHEMA = '$dbName'";
		$res  = mysql_query($qry) or die(mysql_error());
                
        $validUpdateCondition = getValidUpdateCondition($dbName, $tblName);
	
		$strTriger = "CREATE TRIGGER after_".$tblName."_update <br>
    			  AFTER UPDATE ON  ".$tblName."
    		     <br> FOR EACH ROW ";
	
		$trigerForInsert = "CREATE TRIGGER after_".$tblName."_insert
    				AFTER INSERT ON ".$tblName."
    				FOR EACH ROW ";
	
		$tblName = "_t_".$tblName;
	
		$strTriger .="<br> &nbsp;&nbsp;&nbsp;INSERT INTO ".$tblName." SET ";
		$trigerForInsert .="<br> &nbsp;&nbsp;&nbsp;INSERT INTO ".$tblName." SET ";
	
		$qryStr = 'CREATE TABLE '.$tblName." ( _t_transaction_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,<br>";
	
		$columns = array();
		$cnt = 0;
		while($data = mysql_fetch_assoc($res))
		{
			$cnt++;
			$strTriger .= "&nbsp;&nbsp;&nbsp;".$data['COLUMN_NAME']." = NEW.".$data['COLUMN_NAME'].",<br>";
			$trigerForInsert .= "&nbsp;&nbsp;&nbsp;".$data['COLUMN_NAME']." = NEW.".$data['COLUMN_NAME'].",<br>";
			if($data['IS_NULLABLE'] == 'NO')
				$nl = 'NOT NULL';
			else
				$nl = 'NULL';
	
			if($data['COLUMN_DEFAULT'] == '')
				$dflt = '';
			else
				$dflt = ($data['COLUMN_DEFAULT']=='CURRENT_TIMESTAMP') ? "DEFAULT ".$data['COLUMN_DEFAULT'] : "DEFAULT '".$data['COLUMN_DEFAULT']."'";
	
			$qryStr .= $data['COLUMN_NAME']." ".$data['COLUMN_TYPE']." ".$nl." ".$dflt.",<br>";
			$columns[] = $data['COLUMN_NAME'];
		}
		$qryStr .="_t_transaction_date DATETIME,
				 <br> _t_operation enum('U', 'I', 'D'),
                                 <br>_t_mysql_user varchar(100)";
		//echo $qryStr = $qryStr." )";
	
		$strTriger .= "&nbsp;&nbsp;&nbsp;_t_transaction_date = NOW(),<br>
			      &nbsp;&nbsp;&nbsp;_t_operation = 'U',<br>
			      &nbsp;&nbsp;&nbsp;_t_mysql_user = current_user()";
	
		$trigerForInsert .= "&nbsp;&nbsp;&nbsp;_t_transaction_date = NOW(),<br>
					     &nbsp;&nbsp;&nbsp;_t_operation = 'I',<br>
                                             &nbsp;&nbsp;&nbsp;_t_mysql_user = current_user()";
	
		$strForTrgrInsrt = implode(",", $columns);
		$newInsertQry = "INSERT INTO
		$tblName ( $strForTrgrInsrt,_t_transaction_date,_t_operation,_t_mysql_user)
		SELECT
		$strForTrgrInsrt,NOW(),'I', current_user()
		FROM ".str_replace("_t_","",$tblName);
	
		$trigerForDelete = str_replace("_update","_delete",$strTriger);
		$trigerForDelete = str_replace("AFTER UPDATE","AFTER DELETE",$trigerForDelete);
		$trigerForDelete = str_replace("operation = 'U'","operation = 'D'",$trigerForDelete);
		$trigerForDelete = str_replace("NEW.","OLD.",$trigerForDelete);
		echo "-- ".$cnt." Here are audit table/triger and starting insert statement for ".str_replace("_t_","",$tblName)." Table<br><br>";
                
        echo "DROP TABLE IF EXISTS $tblName;<br>";
		echo $qryStr = $qryStr." );<br><br>";
                
        $tblName = str_replace("_t_","",$tblName);
                
        echo "DROP TRIGGER IF EXISTS after_".$tblName."_update;<br>";
		echo $strTriger.";<br><br>";
                
        echo "DROP TRIGGER IF EXISTS after_".$tblName."_insert;<br>";
		echo $trigerForInsert.";<br><br>";                
                
        echo "DROP TRIGGER IF EXISTS after_".$tblName."_delete;<br>";
		echo $trigerForDelete.";<br><br>";
               
		echo $newInsertQry.";<br><br><br><br>";
        
        echo "CONDITION TO SKIP DUPLICATE INERT IN TRIGGER TABLE <br> $validUpdateCondition";
                
	}
        
    function getValidUpdateCondition($dbName, $tableName){
        mysql_query('SET SESSION group_concat_max_len = 1000000;');
        $sql = "select GROUP_CONCAT(a SEPARATOR ' OR ') cond from (select CONCAT('OLD.', COLUMN_NAME, '<>NEW.', COLUMN_NAME) a from information_schema.COLUMNS where TABLE_SCHEMA = '$dbName' and TABLE_NAME = '$tableName') t";
        $res = mysql_fetch_assoc(mysql_query($sql));
        return $res['cond'];
    }
?>