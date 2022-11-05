<?php

/**
 * @name      Bookmarks
 * @author    Aaron
 * @license   BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 1.0
 *
 */

// If we have found SSI.php and we are outside of ELK, then we are running standalone.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('ELK'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('ELK')) // If we are outside ELK and can't find SSI.php, then throw an error
	die('<b>Error:</b> Cannot install - please verify you put this file in the same place as Elkarte\'s SSI.php.');

global $db_prefix;

/**
 * Create the bookmarks table
 */
$dbtbl = db_table();
$dbtbl->db_create_table($db_prefix . 'bookmarks',
	array(
		array(
			'name' => 'id_member',
			'type' => 'mediumint',
			'size' => 8,
            'unsigned' => true,
		),
		array(
			'name' => 'id_topic',
			'type' => 'mediumint',
			'size' => 8,
            'unsigned' => true,
		),
		array(
			'name' => 'id_msg',
			'type' => 'mediumint',
			'size' => 8,
            'unsigned' => true,
		),
		array(
			'name' => 'added_time',
			'type' => 'int',
			'size' => 10,
			'null' => false,
			'unsigned' => true,
			'default' => 0,
		),
	),
	array(
		array(
			'name' => 'bookmark',
			'type' => 'unique',
			'columns' => array('id_member', 'id_topic', 'id_msg'),
		),
		array(
			'name' => 'bookmark2',
			'type' => 'unique',
			'columns' => array('id_member', 'id_msg'),
		),
	),
	array(),
	'ignore');

$dbtbl->db_create_table($db_prefix . 'bookmarks_members',
	array(
		array(
			'name' => 'id_owner',
			'type' => 'mediumint',
			'size' => 8,
            'unsigned' => true,
		),
		array(
			'name' => 'id_member',
			'type' => 'mediumint',
			'size' => 8,
            'unsigned' => true,
		),
		array(
			'name' => 'added_time',
			'type' => 'int',
			'size' => 10,
			'null' => false,
			'unsigned' => true,
			'default' => 0,
		),
	),
	array(
		array(
			'name' => 'bookmark',
			'type' => 'unique',
			'columns' => array('id_member', 'id_topic', 'id_msg'),
		),
		array(
			'name' => 'bookmark2',
			'type' => 'unique',
			'columns' => array('id_member', 'id_msg'),
		),
	),
	array(),
	'ignore');
