<?php

/**
 * @package "Bookmarks" Addon for Elkarte
 * @author Aaron
 * @license BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 3.0.0
 *
 */

/**
 * - Not a lot of settings for this addon so we add them under the predefined
 * Miscellaneous area of the forum
 *
 * @param array $config_vars
 */
function bmks_integrate_general_mod_settings(&$config_vars)
{
	loadLanguage('Bookmarks');

	$config_vars = array_merge($config_vars, array(
		array('check', 'bookmarks_enabled'),
		'',
	));
}

/**
 * - Permissions hook, called from ManagePermissions.php
 * - used to add new permissions
 *
 * @param array $permissionGroups
 * @param array $permissionList
 * @param array $leftPermissionGroups
 * @param array $hiddenPermissions
 * @param array $relabelPermissions
 */
function bmks_integrate_load_permissions(
	&$permissionGroups,
	&$permissionList,
	&$leftPermissionGroups,
	&$hiddenPermissions,
	&$relabelPermissions
) {
	global $context;

	loadLanguage('Bookmarks');

	// Guests should never be able to make bookmarks
	$context['non_guest_permissions'][] = 'bookmarks';

	// Allow admins to grant users the ability to create bookmarks
	$permissionList['membergroup']['make_bookmarks'] = array(false, 'general', 'view_basic_info');
}

// todo: delete this
/**
 * used to remove information when a topic is being removed
 *
 * @param int[] $topics
 */
function bmks_integrate_remove_topics($topics)
{

}

function bmks_integrate_remove_message($message)
{
	global $user_info;

	require_once(SUBSDIR . '/Bookmarks.subs.php');
	deleteBookmarksMessages($user_info['id'], [$message]);
}

// todo: delete this
/**
 * called from Display.controller
 * @param array $topic_selects
 * @param array $topic_tables
 * @param array $topic_parameters
 */
function bmks_integrate_topic_query(&$topic_selects, &$topic_tables, &$topic_parameters)
{
	
}

// todo: delete this
/**
 * called from Display.controller
 * @param array $topicinfo
 */
function bmks_integrate_display_topic($topicinfo)
{
	
}

// todo: delete this
/**
 * called from Display.controller
 *
 * - Used to add additional buttons to topic views
 */
function bmks_integrate_display_buttons()
{
	
}

// Messages.subs.php
function bmks_integrate_message_query(&$msg_selects, &$msg_tables, &$msg_parameters)
{
	global $modSettings, $context, $user_info;

	$context['can_make_bookmarks'] = !empty($modSettings['bookmarks_enabled']) && allowedTo('make_bookmarks');

	if (!$context['can_make_bookmarks'])
	{
		return;
	}

	$msg_selects[] = 'bmk.id_msg AS bookmark';
	$msg_tables[] = '
			LEFT JOIN {db_prefix}bookmarks_messages AS bmk ON (bmk.id_member = {int:bmk_member}
				AND bmk.id_msg = m.id_msg)';
	$msg_parameters['bmk_member'] = $user_info['id'];
}

/**
 * called from Display.controller
 */
function bmks_integrate_before_prepare_display_context(&$message)
{
	global $context, $scripturl, $txt;

	$context['has_bookmark'] = !empty($message['bookmark']);

	$context['additional_drop_buttons']['star_button'] = [
		'href' => $scripturl . '?action=bookmarks;sa='.
			($context['has_bookmark'] ? 'delete' : 'add')
			// . ';topic=' . $context['current_topic']
			. ';msg=' . $message['id_msg'] .
			';' . $context['session_var'] . '=' . $context['session_id'],
		// 'text' => 'Bookmark',
		'text' => $context['has_bookmark'] ? $txt['bmk_remove'] : $txt['bmk_add'],
	];
}

/**
 * called from Subs.php
 *
 * - Used to add top menu buttons
 *
 * @param mixed[] $buttons
 */
function bmks_integrate_menu_buttons(&$buttons)
{
	global $scripturl, $txt, $modSettings;

	$bookmarks_off = empty($modSettings['bookmarks_enabled']) || !allowedTo('make_bookmarks');

	if ($bookmarks_off)
	{
		return;
	}

	loadLanguage('Bookmarks');

	// Where do we want to place the My Bookmarks button
	$insert_after = 'theme';

	// Define the new menu item(s), this will call for GoogleMap.controller
	$new_menu = [
		'bookmarks' => [
			'title' => $txt['bookmarks'],
			'href' => $scripturl . '?action=bookmarks',
			'show' => true,
		]
	];

	$buttons['profile']['sub_buttons'] = elk_array_insert($buttons['profile']['sub_buttons'], $insert_after, $new_menu, 'after');
}


function bmks_integrate_load_member_data(&$select_columns, &$select_tables, $set)
{
	global $user_info;

	if ($set !== 'profile') {
		return;
	}

	$select_tables .= '
			LEFT JOIN {db_prefix}bookmarks_members AS bmk ON (bmk.id_member = mem.id_member)
				AND bmk.id_owner = '.intval($user_info['id']);

	$select_columns .= ',
			bmk.id_member AS bookmark';
}

function bmks_integrate_member_context($user, $display_custom_fields)
{
	global $memberContext, $user_profile;
	// , $scripturl, $txt;

	$b = isset($user_profile[$user]['bookmark']) ? $user_profile[$user]['bookmark'] : null;
	$memberContext[$user]['bookmark'] = $b;
	// echo '<hr>';
	// dump($memberContext[$user]);
	// die;
}
