<?php

// Model integration for bank list
class IsMetrics extends ActiveRecord\Model
{
    static $table_name = 'is_metrics';
    function getData($month) {
        $makeDate = mktime(0,0,0,date('m')-$month,date('d'),date('Y'));
        $newDate = date('Y-m-d',$makeDate);
        $allMetricsData = IsMetrics::find('all',array('conditions'=>array("month > '$newDate'"),'group' => 'month'));
        
        return $allMetricsData;
    }
}