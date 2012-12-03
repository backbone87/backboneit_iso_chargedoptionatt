<?php

$GLOBALS['TL_HOOKS']['replaceInsertTags']['bbit_iso_coa']
	= array('IsotopeChargedOptionAttribute', 'hookReplaceInsertTags');

$GLOBALS['ISO_HOOKS']['productAttributes']['bbit_iso_coa']
	= array('IsotopeChargedOptionAttribute', 'hookProductAttributes');
$GLOBALS['ISO_HOOKS']['calculatePrice']['bbit_iso_coa']
	= array('IsotopeChargedOptionAttribute', 'hookCalculatePrice');
