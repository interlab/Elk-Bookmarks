<?php

/**
 * @package "Bookmarks" Addon for Elkarte
 * @author Aaron
 * @license BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 1.0.1
 *
 */

class Bookmarks_Controller extends Action_Controller
{
	/**
	 * Holds the results of our add/delete actions
	 *
	 * @var string
	 */
	protected $_result;

	protected string $bmk_type = 'messages';
	protected bool $is_members = false;

	/**
	 * Default action method, if a specific bookmark method wasn't
	 * directly called. Simply forwards to main.
	 */
	public function action_index()
	{
		$this->action_bookmarks_main();
	}

	/**
	 * Entry point for all bookmark actions
	 */
	public function action_bookmarks_main()
	{
		global $txt, $context, $scripturl;

		// Actions here
		require_once(SUBSDIR . '/Action.class.php');

		if (isset($_GET['type']) && $_GET['type'] === 'members') {
			$this->bmk_type = 'members';
			$this->is_members = true;
		}
		$context['bmk_is_members'] = $this->is_members;

		// All we know
		$subActions = [
			'main' => [$this, 'action_bookmarks_get', 'permission' => 'make_bookmarks'],
			'add' => [$this, 'action_bookmarks_add', 'permission' => 'make_bookmarks'],
			'delete' => [$this, 'action_bookmarks_delete', 'permission' => 'make_bookmarks'],
		];

		// Your bookmark activity will end here if you don't have permission.
		$action = new Action();

		// Load the template and language
		loadTemplate('Bookmarks');
		loadLanguage('Bookmarks');

		// db help is here
		require_once(SUBSDIR . '/Bookmarks.subs.php');

		// Set the page title
		$context['page_title'] = $txt['bookmarks'];

		// Add it to the linktree
		$context['linktree'][] = [
			'url' => $scripturl . '?action=bookmarks',
			'name' => $txt['bookmarks'],
		];

		// Default to sub-action 'main' if they have asked for something odd
		$subAction = $action->initialize($subActions, 'main');

		$context['sub_action'] = $subAction;

		// Call the right function
		$action->dispatch($subAction);

		// Set any messages
		if (!empty($this->_result))
		{
			$context['bookmark_result'] = is_array($this->_result) ? sprintf($txt[$this->_result[0]], $this->_result[1]) : $txt[$this->_result];
		}
	}

	/**
	 * Load this users bookmarks in to context for display
	 */
	public function action_bookmarks_get()
	{
		global $user_info, $context, $scripturl;

		if ($this->is_members) {
			$total = getCountBookmarksMembers($user_info['id']);
		} else {
			$total = getCountBookmarksMessages($user_info['id']);
		}
		if (!$total) {
			$context['bookmarks'] = [];

			return;
		}

		$offset = empty($_GET['start']) ? 0 : (int) $_GET['start'];
		$limit = 25;

		if ($this->is_members) {
			$context['sub_template'] = 'members';
		}
		$bmk_url = $scripturl . '?action=bookmarks' .
			($this->is_members ? ';type=members' : '');

		$context['page_index'] = constructPageIndex($bmk_url, $offset, $total, $limit);
		$context['page_info'] = [
			'current_page' => $offset / $limit + 1,
			'num_pages' => floor(($total - 1) / $limit) + 1,
		];

		// Load this user's bookmarks
		if ($this->is_members) {
			$context['bookmarks'] = getBookmarksMembers($user_info['id'], $offset, $limit);
		} else {
			$context['bookmarks'] = getBookmarksMessages($user_info['id'], $offset, $limit);
		}

		// if ($type_is_members) {
			$context['can_reply'] = true;
			$context['can_print'] = true;

			// Build the normal button array.
			$context['normal_buttons'] = [
				'messages' => [
					'test' => 'can_reply',
					'text' => 'bmk_messages',
					//'image' => 'reply.png',
					'lang' => true,
					'url' => $scripturl . '?action=bookmarks',
					'active' => !$this->is_members,
				],
				'members' => [
					'test' => 'can_print',
					'text' => 'bmk_members',
					//'image' => 'print.png',
					'lang' => true,
					//'custom' => 'rel="nofollow"',
					//'class' => 'new_win',
					'url' => $scripturl . '?action=bookmarks;type=members',
					'active' => $this->is_members,
				]
			];
		// }
	}

	/**
	 * Adds a bookmark for a certain topic for a certain user.
	 */
	public function action_bookmarks_add()
	{
		global $user_info, $context;

		checkSession('get');

		// No topic, can't add a bookmark then
		if (empty($_GET['u']) && (empty($_GET['topic']) || empty($_GET['msg']))) {
			$this->_result = 'bmk_add_failed';
		} elseif (!empty($_GET['u'])) {
			$id_member = (int) $_GET['u'];

			// Add a bookmark for this user and topic
			$result = addBookmarkMember($user_info['id'], $id_member);
			$this->_result = $result == 0 ? 'bmk_member_add_failed' : 'bmk_member_add_success';
			$this->bmk_type = 'members';
			$this->is_members = true;
		} else {
			$id_topic = (int) $_GET['topic'];
			$id_msg = (int) $_GET['msg'];

			// Add a bookmark for this user and topic
			$result = addBookmark($user_info['id'], $id_topic, $id_msg);
			$this->_result = $result == 0 ? 'bmk_add_failed' : 'bmk_add_success';
		}

		// reLoad this user's bookmarks
		$this->action_bookmarks_get();
	}

	/**
	 * Delete bookmarks for a certain user.
	 */
	public function action_bookmarks_delete()
	{
		global $user_info;

		checkSession('post');

		// None to remove, what are you doing?
		if (empty($_POST['remove_bookmarks'])) {
			$this->_result = 'bookmark_delete_failure';
		} else {
			$ids = [];

			// Make sure we have valid id's here.
			foreach ($_POST['remove_bookmarks'] as $id) {
				$ids[] = (int) $id;
			}

			if ($this->is_members) {
				$result = deleteBookmarksMembers($user_info['id'], $ids);
			} else {
				$result = deleteBookmarksMessages($user_info['id'], $ids);
			}
			// Return the amount of deleted bookmarks, unless an error occurred.
			$this->_result = $result ? ['bookmark_delete_success', $result] : 'bookmark_delete_failure';
		}

		// reLoad this user's bookmarks
		$this->action_bookmarks_get();
	}
}

