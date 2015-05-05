<?php

// Model integration for bank list
class ProptigerAdmin extends ActiveRecord\Model {

    static $table_name = 'proptiger_admin';
    static $ADMIN_TYPES = array(
        "administrator" => "ADMINISTRATOR",
        "data_entry" => "DATAENTRY",
        "call_center" => "CALLCENTER",
        "audit_1" => "AUDIT-1",
        "new_project_audit" => "NEWPROJECTAUDIT",
        "field" => "SURVEY",
        "resale_call_center" => "RESALE-CALLCENTER"
    );
    static $TEAM_LEADER = "teamLeader";

    function getAllExecByDepartment($department) {
        $conditions = array("department = ? ", $department);
        $getAllExec = ProptigerAdmin::find('all', array("conditions" => $conditions));
        return $getAllExec;
    }

    static function getUserInfoByID($adminId) {
        $admin = ProptigerAdmin::find($adminId);
        return $admin;
    }

    static function getAllUsers() {
        $adminData = array();
        $admins = ProptigerAdmin::find('all', array('select' => 'adminid,fname', 'conditions' => array('status' => 'Y'), 'order' => 'trim(fname)'));
        
        foreach($admins as $row){
            $adminData[$row->adminid] = $row->fname;
        }
        
        return $adminData;
    }

}
