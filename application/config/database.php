<?php
defined('BASEPATH') or exit('No direct script access allowed');
$active_group = 'default';
$query_builder = TRUE;
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',

	'username' => 'root',
	'password' => '',
	'database' => 'aallskyt_allsky_tech_pos',

	// 'username' => 'aallskyt_pos_user',
	// 'password' => '[[ILvJt6d74y',
	// 'database' => 'aallskyt_allsky_tech_pos',


	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
