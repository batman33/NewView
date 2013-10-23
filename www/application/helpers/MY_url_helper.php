<?php

/**
 * Anchor Link
 *
 * Creates an anchor based on the local URL.
 *
 * @access	public
 * @param	string	the URL
 * @param	string	the link title
 * @param	mixed	any attributes
 * @return	string
 */
if ( ! function_exists('anchor_user'))
{
	function anchor_user($id = '', $user_name = '', $permision)
	{
		$user_name = (string) $user_name;

		if ($user_name == '')
		{
			$user_name = 'Профиль пользователя';
		}

		if ($id == '')
		{
			return $user_name;
		}

		if ($permision)
		{
			return '<a href="' . base_url() . 'user/viewUser/' . $id . '">' . $user_name . '</a><a href="' . base_url() . 'user/newMessage/' . $id . '" class="sent-message"></a>';
		} else {
			return $user_name;
		}
	}
}