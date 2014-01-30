<?php

// Model integration for resi_project
require_once "support/objects.php";
class ProjectBanks extends ActiveRecord\Model
{
    static $table_name = 'project_banks';
    static function projectBankDeleteInsert($bankList,$projectId) {
      //  echo $projectId;die;
        //$qry = "delete from project_banks where project_id = $projectId";
        ProjectBanks::delete_all(array('conditions' => 
                      array('project_id = ?', $projectId)));
        foreach( $bankList as $bankId ) {
           $insertBank = new ProjectBanks(); 
           $insertBank->project_id = $projectId;
           $insertBank->bank_id = $bankId;
           $insertBank->save();
        }
        
    }

}
