<?php
/***************************************************************
*  Copyright notice
*
*  (c)  2003 d.k.d internet gmbh (info@dkd.de)
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Plugin 'BE-Login Button for FE Users' for the 'dkd_feuser_belogin' extension.
 *
 * @author	Ingmar Schlecht <ingmars@web.de>
 * @coauthor Olivier Dobberkau <olivier.dobberkau@dkd.de>
 * @patches supported by Andreas Kuhn and Ursula Klinger of punkt.de :-)
 * @patches supported by Timo Proescholdt timo at proescholdt.de
 * @
 *
 */


require_once(PATH_tslib."class.tslib_pibase.php");

class tx_dkdfeuserbelogin_pi1 extends tslib_pibase {
	var $prefixId = "tx_dkdfeuserbelogin_pi1";		// Same as class name
	var $scriptRelPath = "pi1/class.tx_dkdfeuserbelogin_pi1.php";	// Path to this script relative to the extension dir.
	var $extKey = "dkd_feuser_belogin";	// The extension key.

	/**
	 * The only lonely function of this plugin.
	 */
	function main($content,$conf)	{
		global $_COOKIE, $key,$value;



		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		$page_id = $GLOBALS["TSFE"]->id;

		$is_fe_user = 0;
		$is_be_user = 0;

		# patch by julian kleinhans from marketing factory
		if (is_array($GLOBALS['TSFE']->fe_user->user)) {
			$fe_user = $GLOBALS['TSFE']->fe_user->user;
			$is_fe_user = 1;
		}

		if ($_COOKIE["be_typo_user"]) {
			# patch by Mauro Lorenzutti of webformat.com to work with DBAL
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery("*", "be_sessions", "ses_id='".$GLOBALS['TYPO3_DB']->fullQuoteStr($_COOKIE["be_typo_user"],"be_sessions")."'");
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 1)	{
				$be_user = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

				# patch by Mauro Lorenzutti of webformat.com to chek if the be_user logged-in is the related be_user of the fe_user, outherwise it shows the login form
				if ($be_user['ses_userid'] == $fe_user['tx_dkdfeuserbelogin_relatedbeuser'])	{
					$is_be_user = 1;
				}
			}
		}

		if(!$is_be_user&&$is_fe_user) {
			# patch by Mauro Lorenzutti of webformat.com to work with DBAL
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery("be.username, be.password, fe.tx_dkdfeuserbelogin_relatedbeuser", "fe_users AS fe, be_users AS be", "fe.uid = ".intval($fe_user['ses_userid'])." AND be.uid=tx_dkdfeuserbelogin_relatedbeuser AND be.deleted!=1");

			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) == 1)	{
				// login for backend user
				$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

				# patch by ursula klinger of punkt.de to work with typo3 version 3.8

				$challenge = md5(uniqid(""));
				$userident = md5($row["username"].":".$row["password"].":".$challenge);
				// Start the Session
				session_start();
				$_SESSION['login_challenge'] = $challenge;


				$content.='
					<form target="_top" action="typo3/index.php" method="POST" name="loginform" autocomplete="off">
					<input type="Hidden" name="username" value="'.htmlspecialchars($row["username"]).'">
					<input type="Hidden" name="p_field" value="">
					<input type="Hidden" name="userident" value="'.htmlspecialchars($userident).'">
					<input type="Hidden" name="challenge" value="'.htmlspecialchars($challenge).'">
					<input type="Hidden" name="redirect_url" value="../index.php?id='.intval($page_id).'">
					<input type="Hidden" name="loginRefresh" value="">
					<input type="Hidden" name="login_status" value="login">
					<!-- <input type="hidden" name="interface" value="frontend"> -->
					<img src="clear.gif" width="3" height="15">
					<input type="Submit" name="commandLI" value="' . htmlspecialchars($this->pi_getLL('login', 'CMS LogIn')) . '">
					</form>';
			}
		}

		if($is_be_user) {
			$content.= str_replace('###LOGOUTLINK###','typo3/logout.php?redirect=../index.php?id='.intval($page_id).'&ATBE=1&sendLogoutSignal=1',$this->conf["logoutButton"]);
		}

		return $this->pi_wrapInBaseClass($content);
	}
}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/dkd_feuser_belogin/pi1/class.tx_dkdfeuserbelogin_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/dkd_feuser_belogin/pi1/class.tx_dkdfeuserbelogin_pi1.php"]);
}

?>