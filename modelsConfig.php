<?php
include_once dirname(__FILE__) . '/external_lib/php-activerecord/ActiveRecord.php';

// Makes connection with database
ActiveRecord\Config::initialize(function($cfg)
{
  $cfg->set_model_directory(".");
  $cfg->set_connections(array('development' =>
    'mysql://root:proptiger@localhost/cms'));
});
ActiveRecord\DateTime::$DEFAULT_FORMAT = 'Y-m-d';
$ar_adapter = ActiveRecord\ConnectionManager::get_connection();
$ar_adapter->connection->query("SET time_zone = '+05:30'");
#ActiveRecord\DateTime::$DEFAULT_FORMAT = 'Y-m-d H-i-s';

// Includes whole model directory
foreach (glob(dirname(__FILE__)."/models/*.php") as $filename)
{
    include_once $filename;
}
