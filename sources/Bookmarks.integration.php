<?php

/**
 * @package "Bookmarks" Addon for Elkarte
 * @author Aaron
 * @license BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 1.0
 *
 */

if (!defined('ELK'))
	die('No access...');

/**
 * Integration hook, integrate_general_mod_settings
 *
 * - Not a lot of settings for this addon so we add them under the predefined
 * Miscellaneous area of the forum
 *
 * @param mixed[] $config_vars
 */
function igm_bookmarks(&$config_vars)
{
	loadLanguage('Bookmarks');

	$config_vars = array_merge($config_vars, array(
		array('check', 'bookmarks_enabled'),
		'',
	));
}

/**
 * ilp_bookmarks()
 *
 * - Permissions hook, integrate_load_permissions, called from ManagePermissions.php
 * - used to add new permissions
 *
 * @param mixed[] $permissionGroups
 * @param mixed[] $permissionList
 * @param mixed[] $leftPermissionGroups
 * @param mixed[] $hiddenPermissions
 * @param mixed[] $relabelPermissions
 */
function ilp_bookmarks(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	global $context;

	loadLanguage('Bookmarks');

	// Guests should never be able to make bookmarks
	$context['non_guest_permissions'][] = 'bookmarks';

	// Allow admins to grant users the ability to create bookmarks
	$permissionList['membergroup']['make_bookmarks'] = array(false, 'general', 'view_basic_info');
}

/**
 * irt_bookmarks
 *
 * integrate_remove_topics, used to remove information when a topic is being removed
 *
 * @param int[] $topics
 */
function irt_bookmarks($topics)
{
	require_once(SUBSDIR . '/Bookmarks.subs.php');
	delete_topic_bookmark($topics);
}

/**
 * integrate_display_buttons hook, called from Display.controller
 *
 * - Used to add additional buttons to topic views
 */
function idb_bookmarks()
{
	global $context, $scripturl, $modSettings;

	// Not enabled ...
	if (empty($modSettings['bookmarks_enabled']))
		return;

	loadLanguage('Bookmarks');

	// First determine if they can make bookmarks
	$context['can_make_bookmarks'] = allowedTo('make_bookmarks');

	// Define the new button
	$bookmarks = array('bookmarks' => array(
		'test' => 'can_make_bookmarks',
		'text' => 'bookmark',
		'image' => 'bookmark.png',
		'lang' => true,
		'url' => $scripturl . '?action=bookmarks;sa=add;topic=' . $context['current_topic'] . ';' . $context['session_var'] . '=' . $context['session_id']
	));

	// Add bookmark to the normal button array
	$context['normal_buttons'] =  elk_array_insert($context['normal_buttons'], 'reply', $bookmarks, 'after');
}

/**
 * integrate_menu_buttons hook, called from Subs.php
 *
 * - Used to add top menu buttons
 *
 * @param mixed[] $buttons
 */
function imb_bookmarks(&$buttons)
{
	global $scripturl, $txt, $modSettings;

	// Not enabled ...
	if (empty($modSettings['bookmarks_enabled']))
		return;

	loadLanguage('Bookmarks');

	// Where do we want to place the My Bookmarks button
	// $insert_after = empty($modSettings['bookmarks_buttonLocation']) ? 'theme' : $modSettings['bookmarks_buttonLocation'];
	$insert_after = 'memberlist';

	// Define the new menu item(s), this will call for GoogleMap.controller
	$new_menu = array(
		'bookmarks' => array(
			'title' => $txt['bookmarks'],
			'href' => $scripturl . '?action=bookmarks',
			'show' => !empty($modSettings['bookmarks_enabled']) && allowedTo('make_bookmarks'),
		)
	);

	$buttons['profile']['sub_buttons'] = elk_array_insert($buttons['profile']['sub_buttons'], $insert_after, $new_menu, 'after');
}