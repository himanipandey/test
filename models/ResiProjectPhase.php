<?php

// Model integration for bank list
class ResiProjectPhase extends ActiveRecord\Model
{
    static $table_name = 'resi_project_phase';
    static $after_create = array('insert_audit_create');
    static $after_update = array('insert_audit_save');

    public function options(){
        $join = "LEFT JOIN project_options_phases p on (resi_project_options.OPTIONS_ID = p.option_id)";
        return ResiProjectOptions::all(array("joins" => $join, "conditions" => array("phase_id = ?", $this->phase_id)));
    }

    public function add_options($option_ids){
        ProjectOptionsPhases::table()->delete(array('option_id' => $option_ids, 'phase_id' => $this->phase_id));
        $this->new_options($option_ids);
    }

    public function reset_options($option_ids){
        ProjectOptionsPhases::table()->delete(array('phase_id' => $this->phase_id));
        $this->new_options($option_ids);
    }

    private function new_options($option_ids){
        foreach($option_ids as $id){
            $map = new ProjectOptionsPhases();
            $map->option_id = $id;
            $map->phase_id = $this->phase_id;
            $map->save();
        }
    }

    public function insert_audit($action){
        $audit = new Audit();
        $audit->row_id = $this->phase_id;
        $audit->action_date = date("Y-m-d H:i:s");
        $audit->table_name = self::$table_name;
        $audit->action = $action;
        $audit->project_id = $this->project_id;
        // Todo: remove this hardcoded id
        $audit->done_by = $_SESSION['adminId'];
        $audit->save();
    }

    public function insert_audit_create(){
        self::insert_audit("create");
    }

    public function insert_audit_save(){
        self::insert_audit("update");
    }
}