<?php
include_once dirname(__FILE__) . '/external_lib/php-activerecord/ActiveRecord.php';

// Makes connection with database
ActiveRecord\Config::initialize(function($cfg)
{
  $cfg->set_model_directory(".");
  $cfg->set_connections(array('development' =>
    'mysql://root:root@localhost/project'));
});

// Includes whole model directory
foreach (glob("models/*.php") as $filename)
{
    include_once $filename;
}