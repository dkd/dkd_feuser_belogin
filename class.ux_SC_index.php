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
 * Redaclared function checkRedirect() for disabling confusing 'No Cookie' warning for the 'dkd_feuser_belogin' extension for TYPO3 3.6.x
 *
 * @author	Rainer Kuhn <t3extensions@punkt.de>
 * (originally Ingmar Schlecht <ingmar@typo3.org>)
 */

class ux_SC_index extends SC_index {					
    
	function checkRedirect()	{
		global $BE_USER,$TBE_TEMPLATE;

			// Do redirect:
			// If a user is logged in AND a) if either the login is just done (commandLI) or b) a loginRefresh is done or c) the interface-selector is NOT enabled (If it is on the other hand, it should not just load an interface, because people has to choose then...)
		if ($BE_USER->user['uid'] && ($this->commandLI || $this->loginRefresh || !$this->interfaceSelector))	{

				// If no cookie has been set previously we tell people that this is a problem. This assumes that a cookie-setting script (like this one) has been hit at least once prior to this instance.
			if (!$GLOBALS["_COOKIE"]["fe_typo_user"]&&!$GLOBALS['_COOKIE'][$BE_USER->name])	{
				t3lib_BEfunc::typo3PrintError ('Login-error',"Yeah, that's a classic. No cookies, no TYPO3.<br /><br />Please accept cookies from TYPO3 - otherwise you'll not be able to use the system.",0);
				exit;
			}

				// Based on specific setting of interface we set the redirect script:
			switch ($this->GPinterface)	{
				case 'backend':
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
		}
	}
}
?>
