<?php

########################################################################
# Extension Manager/Repository config file for ext: "dkd_feuser_belogin"
#
# Auto generated 24-10-2007 17:03
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'FEUser->BELogin',
	'description' => 'Shows a new "CMS LogIn" button to frontend users to switch to editing mode (BE-login) if they are allowed to.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '1.2.8',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => 'fe_users',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Olivier Dobberkau',
	'author_email' => 'olivier.dobberkau@dkd.de',
	'author_company' => 'd.k.d. Internet Service GmbH',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'php' => '3.0.0-0.0.0',
			'typo3' => '3.5.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:12:{s:21:"class.ux_SC_index.php";s:4:"9217";s:12:"ext_icon.gif";s:4:"61ed";s:17:"ext_localconf.php";s:4:"d756";s:14:"ext_tables.php";s:4:"e998";s:14:"ext_tables.sql";s:4:"143c";s:24:"ext_typoscript_setup.txt";s:4:"d18a";s:16:"locallang_db.php";s:4:"123d";s:14:"doc/manual.sxw";s:4:"c301";s:19:"doc/wizard_form.dat";s:4:"039a";s:20:"doc/wizard_form.html";s:4:"74ae";s:37:"pi1/class.tx_dkdfeuserbelogin_pi1.php";s:4:"b519";s:17:"pi1/locallang.php";s:4:"667d";}',
);

?>