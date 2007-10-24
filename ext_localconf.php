<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");

$TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["typo3/index.php"] = t3lib_extMgm::extPath($_EXTKEY)."class.ux_SC_index.php";

t3lib_extMgm::addPItoST43($_EXTKEY,"pi1/class.tx_dkdfeuserbelogin_pi1.php","_pi1","list_type",0);
?>