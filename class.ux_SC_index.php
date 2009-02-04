<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004
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
 * Redaclared function checkRedirect() for disabling confusing 'No Cookie' warning for the 'dkd_feuser_belogin' extension for TYPO3 4.2.x
 *
 * @author	Rainer Kuhn <t3extensions@punkt.de>
 * (originally Ingmar Schlecht <ingmar@typo3.org>)
 */

class ux_SC_index extends SC_index {					
    
	/**
	 * Checking, if we should perform some sort of redirection OR closing of windows.
	 *
	 * @return	void
	 */
	function checkRedirect()	{
		global $BE_USER,$TBE_TEMPLATE;

			// Do redirect:
			// If a user is logged in AND a) if either the login is just done (commandLI) or b) a loginRefresh is done or c) the interface-selector is NOT enabled (If it is on the other hand, it should not just load an interface, because people has to choose then...)
		if ($BE_USER->user['uid'] && ($this->commandLI || $this->loginRefresh || !$this->interfaceSelector))	{

				// If no cookie has been set previously we tell people that this is a problem. This assumes that a cookie-setting script (like this one) has been hit at least once prior to this instance.
 			if (!$_COOKIE['fe_typo_user'] && !$_COOKIE[$BE_USER->name])	{
				if ($this->commandLI=='setCookie') {
						// we tried it a second time but still no cookie
						// 26/4 2005: This does not work anymore, because the saving of challenge values in $_SESSION means the system will act as if the password was wrong.
					t3lib_BEfunc::typo3PrintError ('Login-error',"Yeah, that's a classic. No cookies, no TYPO3.<br /><br />Please accept cookies from TYPO3 - otherwise you'll not be able to use the system.",0);
					exit;
				} else {
						// try it once again - that might be needed for auto login
					$this->redirectToURL = 'index.php?commandLI=setCookie';
				}
			}

			if ($redirectToURL = (string)$BE_USER->getTSConfigVal('auth.BE.redirectToURL')) {
				$this->redirectToURL = $redirectToURL;
				$this->GPinterface = '';
 			}

				// store interface
			$BE_USER->uc['interfaceSetup'] = $this->GPinterface;
			$BE_USER->writeUC();

				// Based on specific setting of interface we set the redirect script:
			switch ($this->GPinterface)	{
				case 'backend':
					$this->redirectToURL = 'backend.php';
				break;
				case 'backend_old':
					$this->redirectToURL = 'alt_main.php';
				break;
				case 'frontend':
					$this->redirectToURL = '../';
				break;
			}

				// If there is a redirect URL AND if loginRefresh is not set...
			if (!$this->loginRefresh)	{
				header('Location: '.t3lib_div::locationHeaderUrl($this->redirectToURL));
				exit;
			} else {
				$TBE_TEMPLATE->JScode.=$TBE_TEMPLATE->wrapScriptTags('
					if (parent.opener && parent.opener.busy)	{
						parent.opener.busy.loginRefreshed();
						parent.close();
					}
				');
			}

		} elseif (!$BE_USER->user['uid'] && $this->commandLI) {
			sleep(5);	// Wrong password, wait for 5 seconds
		}
	}
}
?>