<?php

function template_main()
{
	global $context, $settings, $scripturl, $txt;

	// Show the good or bad news, if any.
	if (isset($context['bookmark_result'])) {
		echo '
			<div class="infobox" id="profile_success">
				', $context['bookmark_result'], '
			</div>';
	}

	$not_empty = !empty($context['bookmarks']);

	if ($not_empty) {
		template_pagesection('normal_buttons', 'right');
	}

	// Show the bookmarks, if any.
	if ($not_empty)
	{
		echo '
			<main id="bookmarks">
				<header class="category_header hdicon cat_img_posts">', $txt['bookmark_list'], '</header>';

		foreach ($context['bookmarks'] as $msg)
		{
			// Show the topic's subject
			echo '
					<article class="content forumposts">
				<header class="topic_details">
					<h5>
						<p class="topic_icons', !empty($msg['icon']) ? ' topicicon i-' . $msg['icon'] : '', '">
							', $msg['post']['link'],
							' <i>', $txt['in'], ' ', $msg['board']['link'], '</i>
						</p>
						<br class="clear">
						<p class="smalltext">'
							, $txt['by'], ' <strong>', $msg['last_post']['member']['link'], '</strong> - ', $msg['post']['time'], '
						</p>
					</h5>
				</header>
				<section class="inner">
					', $msg['body'], '
				</section>';

			if (!empty($msg['buttons']))
				template_quickbutton_strip($msg['buttons'], !empty($msg['tests']) ? $msg['tests'] : []);

			echo '
					</article>';
		}
	}
	// Show a message saying there aren't any bookmarks yet
	else
	{
		if ($context['bmk_is_members']) {
			echo '
			<div class="infobox">', $txt['bookmark_members_empty'], '</div>';
		} else { 
			echo '
			<div class="infobox">', $txt['bookmark_messages_empty'], '</div>';
		}
	}

	if ($not_empty)
		template_pagesection('normal_buttons', 'right');
}

function template_members()
{
	global $context, $settings, $scripturl, $txt, $memberContext;

	// Show the good or bad news, if any.
	if (isset($context['bookmark_result'])) {
		echo '
			<div class="infobox" id="profile_success">
				', $context['bookmark_result'], '
			</div>';
	}

	// We know how to sprite these
	$message_icon_sprite = [
		'clip' => '', 'lamp' => '', 'poll' => '', 'question' => '', 'xx' => '',
		'moved' => '', 'exclamation' => '', 'thumbup' => '', 'thumbdown' => ''
	];

	$not_empty = !empty($context['bookmarks']);

	if ($not_empty) {
		template_pagesection('normal_buttons', 'right');
	}

	// Let's get the show moving.
	echo '
			<h3 class="category_header hdicon cat_img_profile">', $txt['bookmark_list'], '</h3>';

	// Show the bookmarks, if any.
	if ($not_empty)
	{
		echo '
			<form class="generic_list_wrapper" action="', $scripturl, '?action=bookmarks;sa=delete;type=members" method="post">
				<table class="table_grid">
					<thead>
						<tr class="table_head">
							<th style="width:50px;">Avatar</th>
							<th class="grid20">Username</th>
							<th class="grid8">Status</th>
							<th class="grid17">Position</th>
							<th class="grid20">Date Registered</th>
							<th class="grid8">Posts</th>
							<th class="grid17">', $txt['bmk_added'], '</th>
							<th class="centertext">
								<input type="checkbox" class="input_check" onclick="invertAll(this, this.form);" />
							</th>
						</tr>
					</thead>
					<tbody>';

		foreach ($context['bookmarks'][1] as $row)
		{
			if (!loadMemberContext($row['id_member'])) {
				continue;
			}

			$time = standardTime($row['added_time']);
			$user = $memberContext[$row['id_member']];

			// Show the topic's subject
			echo '
						<tr>
							<td>', $user['avatar']['image'], '</td>
							<td>
								', $user['link'], '
							</td>
							<td>
								', template_member_online($user), '
							</td>
							<td>
								', $user['group'], '
							</td>
							<td>
								<span class="smalltext">', $user['registered'], '</span>
							</td>
							<td>
								<span class="smalltext">', $user['posts'], '</span>
							</td>
							<td>
								<span class="smalltext">', $time, '</span>
							</td>
							<td class="centertext">
								<input type="checkbox" name="remove_bookmarks[]" value="', $row['id_member'], '" class="input_check" />
							</td>
						</tr>';
		}

		echo '
					</tbody>
				</table>
				<div class="submitbutton">
					<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
					<input class="button_submit" type="submit" name="send" value="', $txt['bookmark_delete'], '" />
				</div>
			</form>';
	}
	// Show a message saying there aren't any bookmarks yet
	else
	{
		echo '
			<div class="infobox">', $txt['bookmark_list_empty'], '</div>';
	}

	if ($not_empty)
		template_pagesection('normal_buttons', 'right');
}
