<?php

//ffs, missing module dependencies requiring this way...
$GLOBALS['TL_HOOKS']['loadDataContainer'][]	= array('IsotopeChargedOptionAttribute', 'hookLoadDataContainer');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]	= array('IsotopeChargedOptionAttribute', 'hookReplaceInsertTags');
$GLOBALS['TL_HOOKS']['sqlGetFromFile'][]	= array('IsotopeChargedOptionAttribute', 'hookSQLGetFromFile');

$GLOBALS['ISO_HOOKS']['productAttributes'][]= array('IsotopeChargedOptionAttribute', 'hookProductAttributes');
$GLOBALS['ISO_HOOKS']['calculatePrice'][]	= array('IsotopeChargedOptionAttribute', 'hookCalculatePrice');

$GLOBALS['TL_FFL']['radio'] = 'FormRadioButtonClean';
