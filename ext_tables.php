<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");
$tempColumns = Array (
	"tx_dkdfeuserbelogin_relatedbeuser" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:dkd_feuser_belogin/locallang_db.php:fe_users.tx_dkdfeuserbelogin_relatedbeuser",		
		"config" => Array (
			"type" => "select",	
			"items" => Array (
				Array("",0),
			),
			"foreign_table" => "be_users",	
			"foreign_table_where" => "AND be_users.pid=###SITEROOT### ORDER BY be_users.uid",	
			"size" => 1,	
			"minitems" => 0,
			"maxitems" => 1,	
			"wizards" => Array(
				"_PADDING" => 2,
				"_VERTICAL" => 1,
				"add" => Array(
					"type" => "script",
					"title" => "Create new record",
					"icon" => "add.gif",
					"params" => Array(
						"table"=>"be_users",
						"pid" => "0",
						"setValue" => "prepend"
					),
					"script" => "wizard_add.php",
				),
			),
		)
	),
);


t3lib_div::loadTCA("fe_users");
t3lib_extMgm::addTCAcolumns("fe_users",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes("fe_users","tx_dkdfeuserbelogin_relatedbeuser;;;;1-1-1");


t3lib_div::loadTCA("tt_content");
$TCA["tt_content"]["types"]["list"]["subtypes_excludelist"][$_EXTKEY."_pi1"]="layout,select_key";

t3lib_extMgm::addPlugin(Array("LLL:EXT:dkd_feuser_belogin/locallang_db.php:tt_content.list_type", $_EXTKEY."_pi1"),"list_type");
?>