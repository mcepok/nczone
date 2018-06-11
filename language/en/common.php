<?php
/**
 *
 * nC Zone. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018, Marian Cepok, https://new-chapter.eu
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'NCZONE' => 'nC Zone',

	'MCP_ZONE_TITLE'		=> 'nC Zone',
	'MCP_PLAYERS_TITLE'		=> 'Player',
	'MCP_CIVS_TITLE'		=> 'Civilizations',
	'MCP_MAPS_TITLE'		=> 'Maps',

	'NCZONE_LOGGEDIN'		=> 'Logged in',
	'NCZONE_LOGIN'			=> 'Login',
	'NCZONE_LOGOUT'			=> 'Logout',
	'NCZONE_DRAW'			=> 'Draw',

	'NCZONE_LINKS'          => 'Links',
	'NCZONE_ZONE'           => 'Teh Zone',
	'NCZONE_INFORMATION'    => 'Max important Information',
	'NCZONE_RMATCHES'       => 'Running Matches',
	'NCZONE_PMATCHES'       => 'Past Matches',
	'NCZONE_TABLE'          => 'Player Table',
));
