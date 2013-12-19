<?php

// Model integration for project_migration
require_once "support/objects.php";
class ProjectMigration extends ActiveRecord\Model
{
    static $table_name = 'project_migration';
}
