<?php

// Model integration for project power backup types
class PowerBackupTypes extends ActiveRecord\Model
{
    static $table_name = 'master_power_backup_types';
    static function getPowerBackupTypes() {
        $getPowerBackupTypes = PowerBackupTypes::find('all');
        return $getPowerBackupTypes;
    }
}