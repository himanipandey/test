<?php

// Model integration for bank list
class ProptigerAdmin extends ActiveRecord\Model
{
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
}