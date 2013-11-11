<?php
	include("builder_function.php");
        include("dbConfig.php");
	$builderId		=	$_REQUEST['builderId'];
	$exp 			= explode("--",$_REQUEST['name']);
	$exp_Del 		= explode("--",$_REQUEST['deleteval']);
	$exp_id 		= explode("--",$_REQUEST['id']);
	$exp_phone 		= explode("--",$_REQUEST['phone']);
	$exp_email 		= explode("--",$_REQUEST['email']);
	$exp_projects 	= explode("--",$_REQUEST['projects']);
	$msg 			= 0;
        $qryDelMap = "delete from project_builder_contact_mappings where 
         builder_contact_id in ( select id from builder_contacts where builder_id = $builderId )";
        mysql_query($qryDelMap) or die(mysql_error());
	foreach($exp as $key=>$val)
	{
		if($val != '' AND $exp_id[$key] != 'undefined')
		{
                    if($exp_Del[$key] == 1)
                    {
                        $qryDel = "DELETE FROM builder_contacts
                                   WHERE
                                      ID = '".$exp_id[$key]."'";
                       $resDel = mysql_query($qryDel) or die(mysql_error());
                       if($resDel)
                           $msg = 1;
                       else
                           $msg = 0;
                    }
                    else
                    {
                        $qrySel = "SELECT * FROM builder_contacts
                                    WHERE 
                                      ID = '".$exp_id[$key]."'";
                        $res    = mysql_query($qrySel);
                        if(mysql_num_rows($res)>0)
                        {
                            $projects = substr($exp_projects[$key], 1);
                            $resUp = "UPDATE builder_contacts
                                    SET 
                                      NAME    = '".$val."',
                                      PHONE   = '".$exp_phone[$key]."',
                                      EMAIL   = '".$exp_email[$key]."'
                                    WHERE
                                      ID      = '".$exp_id[$key]."'";
                            $resUp = mysql_query($resUp);
                            if($resUp)
                            {
                                if(strlen($projects)>0){
                                    $exp = explode(',', $projects);
                                    if(count($exp)>1) {
                                        foreach($exp as $val) {
                                        print $qryIns = "insert into project_builder_contact_mappings set 
                                         project_id = $val,builder_contact_id = '".$exp_id[$key]."'";
                                        $resIns = mysql_query($qryIns) or die(mysql_error());
                                        }
                                    }
                                }
                                $msg = 1;
                            }
                            else 
                               $msg = 0;
                        }
                        else
                        {
                            $resIns = "INSERT INTO builder_contacts
                                    SET 
                                      NAME       = '".$val."',
                                      PHONE      = '".$exp_phone[$key]."',
                                      EMAIL      = '".$exp_email[$key]."', 
                                      BUILDER_ID = '".$builderId."'";
                            $resIns = mysql_query($resIns) or die(mysql_error());
                            $contactId = mysql_insert_id();
                            if($resIns){
                                    if(strlen($projects)>0){
                                    $exp = explode(',', $projects);
                                    if(count($exp)>1) {
                                        foreach($exp as $val) {
                                        $qryIns = "insert into project_builder_contact_mappings set 
                                        project_id = $val,builder_contact_id = '".$contactId."'";
                                        $resIns = mysql_query($qryIns) or die(mysql_error());
                                        }
                                    }
                                }
                                $msg = 1;
                            }
                            else
                               $msg = 0;
                        }
                    }
		}
	}
	echo $msg;
	

?>
