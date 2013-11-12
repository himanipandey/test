<?php

// Model integration for bank list
require_once "support/objects.php";
class ResiProjectPhase extends Objects
{
    static $table_name = 'resi_project_phase';
    static $default_scope = array("version" => "cms");
    static $virtual_primary_key = 'phase_id';

//    static $after_create = array('insert_audit_create');
//    static $after_update = array('insert_audit_save');

    public function get_all_options(){
        $join = "JOIN listings l on (l.option_id = resi_project_options.options_id and l.listing_category = 'Primary' and l.status = 'Active' and option_category = 'Actual')";
        return ResiProjectOptions::all(array("joins" => $join, "conditions" => array("l.phase_id = ?", $this->phase_id)));
    }

    public function add_options($option_ids){
        ProjectOptionsPhases::table()->delete(array('option_id' => $option_ids, 'phase_id' => $this->phase_id));
        $this->new_options($option_ids);
    }

    public function reset_options($option_ids){
        Listings::update_all(array('conditions' => array('phase_id' => $this->phase_id, 'listing_category' => 'Primary'), 'set' => array('status' => 'Inactive')));
        $this->new_options($option_ids);
    }

    private function new_options($option_ids) {
        foreach ($option_ids as $id) {
            if ($id == -1) return;
                
            $existingListings = Listings::find('all', array("joins" => "join resi_project_options o on (o.options_id = option_id and o.option_category = 'Actual')", "conditions" => array("listing_category = 'Primary' and option_id = ? and phase_id = ?", $id, $this->phase_id)));
            if (empty($existingListings)) {
                $map = new Listings();
                $map->option_id = $id;
                $map->phase_id = $this->phase_id;
                $map->listing_category = 'Primary';
                $map->status = 'Active';
                $map->updated_at = date('Y-m-d H:i:s');
                $map->updated_by = $_SESSION['adminId'];
                $map->created_at = date('Y-m-d H:i:s');
                $map->save();
            }
        }

        if (!empty($option_ids)) {
            Listings::update_all(array("joins" => "join resi_project_options o on (o.options_id = option_id and o.option_category = 'Actual')", 'conditions' => array('phase_id' => $this->phase_id, 'listing_category' => 'Primary', 'option_id' => $option_ids), 'set' => array('status' => 'Active')));
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

    function get_phase_option_hash_by_project($projectId){
        $phases = self::all(array('conditions' => array('PROJECT_ID = ?', $projectId)));
        $options = ResiProjectOptions::all(array('conditions' => array('PROJECT_ID = ?', $projectId)));
        $phase_option_hash = array();
        foreach($phases as $phase){
            $phase_option_hash[$phase->phase_id] = $phase->options();
            if(count($phase_option_hash[$phase->phase_id]) == 0) $phase_option_hash[$phase->phase_id]= $options;
        }
        $phase_option_hash[0] = $options;
        return array($phases, $phase_option_hash);
    }

    public function towers(){
        return static::get_towers_for_phases($this->phase_id);
    }

    static function get_towers_for_phases($phase_ids) {
        if(!is_array($phase_ids) || count($phase_ids) > 0) {
            $join = "LEFT JOIN phase_tower_mappings p on (resi_project_tower_details.TOWER_ID = p.tower_id)";
            return ResiProjectTowerDetails::all(array("joins" => $join, "conditions" => array("p.phase_id in (?)",
                $phase_ids)));
        }
        return array();
    }

    public function add_towers($tower_ids){
        ResiProjectTowerDetails::query("delete from phase_tower_mappings where phase_id = ".$this->phase_id);
        if (!empty($tower_ids)) {
            $this->new_towers($tower_ids);
        }
    }

    private function new_towers($tower_ids){
        $condArray = array();
        foreach($tower_ids as $id){
            if ($id == -1) return;
            array_push($condArray,"({$this->phase_id},{$id})");
        }
        ResiProjectTowerDetails::query("insert into phase_tower_mappings(phase_id,tower_id) values ".implode(",",$condArray));
    }

    static function getNoPhaseForProject($projectId){
        return self::find(array('conditions'=>array('PROJECT_ID'=>$projectId)));
    }

}