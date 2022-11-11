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
 * Adds a bookmark for a certain topic / user.
 *
 * @param int $id_member
 * @param int $id_topic
 */
function addBookmark($id_member, $id_msg)
{
	$db = database();

	// Add a bookmark for this user and topic
	$result = $db->insert('replace',
		'{db_prefix}bookmarks_messages',
		[
			'id_member' => 'int',
			// 'id_topic' => 'int',
			'id_msg' => 'int',
			'added_time' => 'int',
		],
		[
			'id_member' => $id_member,
			// 'id_topic' => $id_topic,
			'id_msg' => $id_msg,
			'added_time' => time(),
		],
		[]
	);

	return $db->affected_rows($result);
}

/**
 * Adds a bookmark user
 *
 * @param int $me_id
 * @param int $selected_id
 */
function addBookmarkMember($id_owner, $id_member)
{
	$db = database();

	// Add a bookmark for this user and topic
	$result = $db->insert('replace', 
		'{db_prefix}bookmarks_members',
		[
			'id_owner' => 'int',
			'id_member' => 'int',
			'added_time' => 'int',
		],
		[
			'id_owner' => $id_owner,
			'id_member' => $id_member,
			'added_time' => time(),
		],
		[]
	);

	return $db->affected_rows($result);
}

/**
 * Delete bookmarks messages for a certain user.
 *
 * @param int $id_member
 * @param int[] $topic_ids
 */
function deleteBookmarksMessages($id_member, $msgs)
{
	$db = database();

	// Remove what we can
	$result = $db->query('', '
		DELETE FROM {db_prefix}bookmarks_messages
		WHERE
			id_msg IN({array_int:msgs})
		AND
			id_member = {int:id_member}',
		[
			'id_member' => $id_member,
			'msgs' => $msgs,
		]
	);

	// Return the amount of deleted bookmarks, unless an error occurred.
	return $result ? $db->affected_rows() : false;
}

/**
 * Delete bookmarks members for a certain user
 */
function deleteBookmarksMembers($id_owner, $members)
{
	$db = database();

	// Remove what we can
	$result = $db->query('', '
		DELETE FROM {db_prefix}bookmarks_members
		WHERE
			id_member IN({array_int:members})
		AND
			id_owner = {int:id_owner}',
		[
			'id_owner' => $id_owner,
			'members' => $members,
		]
	);

	// Return the amount of deleted bookmarks, unless an error occurred.
	return $result ? $db->affected_rows() : false;
}

function getCountBookmarksMembers($id_owner)
{
	global $modSettings;

	$db = database();

	$request = $db->query('', '
		SELECT COUNT(*)
		FROM {db_prefix}bookmarks_members AS bm
			INNER JOIN {db_prefix}members AS mem ON (mem.id_member = bm.id_owner)
		WHERE
			bm.id_owner = {int:owner}
		LIMIT 1',
		[
			'owner' => $id_owner,
		]
	);
	$result = $db->fetch_row($request);
	$total = empty($result) ? 0 : (int) $result[0];
	$db->free_result($request);

	return $total;
}

/**
 * Gathers a list of all of this user's bookmarks.
 *
 * @param int $id_member
 */
function getBookmarksMembers($id_owner, $offset, $limit)
{
	global $settings, $scripturl, $modSettings, $user_info, $txt, $context;

	$db = database();

	$request = $db->query('', '
		SELECT
			bm.id_member, bm.added_time
		FROM {db_prefix}bookmarks_members AS bm
			INNER JOIN {db_prefix}members AS mem ON (mem.id_member = bm.id_member)
		WHERE
			bm.id_owner = {int:owner}
		ORDER BY bm.added_time DESC
		LIMIT {int:offset}, {int:limit}',
		[
			'owner' => $id_owner,
			'offset' => $offset,
			'limit' => $limit,
		]
	);
	$members = [];
	$bookmarks = [];
	while ($row = $db->fetch_assoc($request)) {
		$bookmarks[] = $row;
		$members[] = $row['id_member'];
	}
	$db->free_result($request);
	$ids = [];
	if (!empty($members)) {
		$ids = loadMemberData($members);
	}
	if (!empty($ids)) {
		$bookmarks = array_filter($bookmarks, function($it) use ($ids) {
			return in_array($it['id_member'], $ids); });
	}
	// $context['can_send_pm'] = allowedTo('pm_send');

	return [$ids, $bookmarks];
}

function getCountBookmarksMessages($id_member)
{
	global $modSettings;

	$db = database();

	$request = $db->query('', '
		SELECT COUNT(t.id_topic)
		FROM {db_prefix}bookmarks_messages AS bm
			INNER JOIN {db_prefix}messages AS m ON (m.id_msg = bm.id_msg)
			INNER JOIN {db_prefix}topics AS t ON (m.id_topic = t.id_topic)
			INNER JOIN {db_prefix}boards AS b ON (t.id_board = b.id_board)
		WHERE
			bm.id_member = {int:current_member}' . (!$modSettings['postmod_active'] || allowedTo('approve_posts') ? '' : '
			AND (t.approved = {int:is_approved} OR t.id_member_started = {int:current_member})') . '
			AND {query_see_board}
		LIMIT 1',
		[
			'current_member' => $id_member,
			'is_approved' => 1,
		]
	);
	$result = $db->fetch_row($request);
	$total = empty($result) ? 0 : (int) $result[0];
	$db->free_result($request);

	return $total;
}

/**
 * Gathers a list of all of this user's bookmarks.
 *
 * @param int $id_member
 */
function getBookmarksMessages($id_member, $offset, $limit)
{
	global $settings, $scripturl, $modSettings, $user_info, $txt, $context;

	$db = database();
	$bbc_parser = \BBC\ParserWrapper::instance();
	$request = $db->query('', '
		SELECT
			t.id_topic, t.num_replies, t.locked, t.num_views, t.id_board, t.id_last_msg, t.id_first_msg,
			t.id_last_msg, m.id_msg,
			b.name AS board_name,
			IFNULL(lt.id_msg, IFNULL(lmr.id_msg, -1)) + 1 AS new_from,
			m.poster_time AS msg_poster_time, m.body AS msg_body, m.id_msg_modified,
			m.subject AS msg_subject, m.smileys_enabled,
			m.icon AS msg_icon, m.poster_name AS msg_member_name, m.id_member AS msg_id_member,
			IFNULL(mem.real_name, m.poster_name) AS msg_display_name,
			ml.poster_time AS last_poster_time, ml.id_msg_modified, ml.subject AS last_subject,
			ml.icon AS last_icon, ml.poster_name AS last_member_name, ml.id_member AS last_id_member,
			IFNULL(meml.real_name, ml.poster_name) AS last_display_name,
			bm.added_time AS bm_added_time
		FROM {db_prefix}bookmarks_messages AS bm
			INNER JOIN {db_prefix}messages AS m ON (m.id_msg = bm.id_msg)
			INNER JOIN {db_prefix}topics AS t ON (m.id_topic = t.id_topic)
			INNER JOIN {db_prefix}boards AS b ON (t.id_board = b.id_board)
			INNER JOIN {db_prefix}messages AS ml ON (ml.id_msg = t.id_last_msg)
			LEFT JOIN {db_prefix}members AS meml ON (meml.id_member = ml.id_member)
			LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = m.id_member)
			LEFT JOIN {db_prefix}log_topics AS lt ON (lt.id_topic = t.id_topic
				AND lt.id_member = {int:current_member})
			LEFT JOIN {db_prefix}log_mark_read AS lmr ON (lmr.id_board = t.id_board
				AND lmr.id_member = {int:current_member})
		WHERE
			bm.id_member = {int:current_member}' . (!$modSettings['postmod_active'] || allowedTo('approve_posts') ? '' : '
			AND (t.approved = {int:is_approved} OR t.id_member_started = {int:current_member})') . '
			AND {query_see_board}
		ORDER BY bm.added_time DESC, t.id_last_msg DESC
		LIMIT {int:offset}, {int:limit}',
		[
			'current_member' => $id_member,
			'is_approved' => 1,
			'offset' => $offset,
			'limit' => $limit,
		]
	);
	$bookmarks = [];
	while ($row = $db->fetch_assoc($request))
	{
		$row['has_bookmark'] = !empty($row['bm_added_time']);

		$bookmarks[$row['id_msg']] = [
			'topic' => [
				'id' => $row['id_topic'],
			],
			'board' => [
				'id' => $row['id_board'],
				'name' => $row['board_name'],
				'href' => $scripturl . '?board=' . $row['id_board'] . '.0',
				'link' => '<a href="' . $scripturl . '?board=' . $row['id_board'] . '.0">' . $row['board_name'] . '</a>'
			],
			'post' => [
				'id' => $row['id_msg'],
				'member' => [
					'id' => $row['msg_id_member'],
					'username' => $row['msg_member_name'],
					'name' => $row['msg_display_name'],
					'href' => !empty($row['msg_id_member']) ? $scripturl . '?action=profile;u=' . $row['msg_id_member'] : '',
					'link' => !empty($row['msg_id_member']) ? '<a href="' . $scripturl . '?action=profile;u=' . $row['msg_id_member'] . '">' . $row['msg_display_name'] . '</a>' : $row['msg_display_name']
				],
				'time' => standardTime($row['msg_poster_time']),
				'timestamp' => forum_time(true, $row['msg_poster_time']),
				//'icon' => $row['last_icon'],
				//'icon_url' => $settings['images_url'] . '/post/' . $row['last_icon'] . '.gif',
				'href' => $scripturl . '?topic=' . $row['id_topic']
	. ($user_info['is_guest'] ? ('.' . (!empty($options['view_newest_first']) 
	? 0 : ((int) (($row['num_replies']) / $context['pageindex_multiplier'])) * $context['pageindex_multiplier']) 
					. '#msg' . $row['id_msg']) : (($row['num_replies'] == 0 ? '.0' : '.msg' . $row['id_msg']) . '#new')),
				'link' => '<a href="' . $scripturl . '?topic=' . $row['id_topic'] 
	. ($user_info['is_guest'] ? ('.' . (!empty($options['view_newest_first']) 
	? 0 : ((int) (($row['num_replies']) / $context['pageindex_multiplier'])) * $context['pageindex_multiplier']) 
	. '#msg' . $row['id_msg']) : (($row['num_replies'] == 0 ? '.0' : '.msg' . $row['id_msg']) . '#new')) 
	. '" ' . ($row['num_replies'] == 0 ? '' : 'rel="nofollow"') . '>' . $row['msg_subject'] . '</a>'
			],
			'last_post' => [
				'id' => $row['id_last_msg'],
				'member' => [
					'username' => $row['last_member_name'],
					'name' => $row['last_display_name'],
					'id' => $row['last_id_member'],
					'href' => !empty($row['last_id_member']) ? $scripturl . '?action=profile;u=' . $row['last_id_member'] : '',
					'link' => !empty($row['last_id_member']) ? '<a href="' . $scripturl . '?action=profile;u=' . $row['last_id_member'] . '">' . $row['last_display_name'] . '</a>' : $row['last_display_name']
				],
				'time' => standardTime($row['last_poster_time']),
				'timestamp' => forum_time(true, $row['last_poster_time']),
				'subject' => $row['last_subject'],
				'icon' => $row['last_icon'],
				'icon_url' => $settings['images_url'] . '/post/' . $row['last_icon'] . '.gif',
				'href' => $scripturl . '?topic=' . $row['id_topic'] . ($user_info['is_guest'] ? ('.' . (!empty($options['view_newest_first']) ? 0 : ((int) (($row['num_replies']) / $context['pageindex_multiplier'])) * $context['pageindex_multiplier']) . '#msg' . $row['id_last_msg']) : (($row['num_replies'] == 0 ? '.0' : '.msg' . $row['id_last_msg']) . '#new')),
				'link' => '<a href="' . $scripturl . '?topic=' . $row['id_topic'] . ($user_info['is_guest'] ? ('.' . (!empty($options['view_newest_first']) ? 0 : ((int) (($row['num_replies']) / $context['pageindex_multiplier'])) * $context['pageindex_multiplier']) . '#msg' . $row['id_last_msg']) : (($row['num_replies'] == 0 ? '.0' : '.msg' . $row['id_last_msg']) . '#new')) . '" ' . ($row['num_replies'] == 0 ? '' : 'rel="nofollow"') . '>' . $row['last_subject'] . '</a>'
			],
			'icon' => $row['msg_icon'],
			'icon_url' => $settings['theme_url'] . '/post/' . $row['msg_icon'] . '.png',

			'body' => $bbc_parser->parseMessage(censor($row['msg_body']), $row['smileys_enabled']),
			'subject' => censor($row['msg_subject']),

			'new' => $row['new_from'] <= $row['id_msg_modified'],
			'new_from' => $row['new_from'],
			'newtime' => $row['new_from'],
			'new_href' => $scripturl . '?topic=' . $row['id_topic'] . '.msg' . $row['new_from'] . '#new',
			'replies' => $row['num_replies'],
			'views' => $row['num_views'],
			'bookmark' => ['time' => standardTime($row['bm_added_time'])],
			'buttons' => [
				'star' => [
					'href' => $scripturl . '?action=bookmarks;sa='.
						($row['has_bookmark'] ? 'delete' : 'add') . ';msg=' . $row['id_msg'] .
						';' . $context['session_var'] . '=' . $context['session_id'],
					// 'custom' => 'star',
					'text' => $row['has_bookmark'] ? $txt['bmk_remove'] : $txt['bmk_add'],
				],
			],
		];
	}
	$db->free_result($request);

	return $bookmarks;
}
