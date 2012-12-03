<?php

$GLOBALS['ISO_ATTR']['bbit_iso_coa']['backend']		= 'multiColumnWizard';
$GLOBALS['ISO_ATTR']['bbit_iso_coa']['sql']			= 'blob NULL';
$GLOBALS['ISO_ATTR']['bbit_iso_coa']['callback'][]	= array('IsotopeChargedOptionAttribute', 'callbackCOA');
$GLOBALS['ISO_ATTR']['bbit_iso_coa']['class']		= 'FormIsotopeChargedOptionAttribute';
