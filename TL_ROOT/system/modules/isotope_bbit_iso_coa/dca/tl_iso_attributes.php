<?php

$GLOBALS['TL_DCA']['tl_iso_attributes']['palettes']['__selector__'][] = 'bbit_iso_coa_feInput';

$GLOBALS['TL_DCA']['tl_iso_attributes']['palettes']['bbit_iso_coa']
	= '{attribute_legend},name,field_name,type,legend'
	. ';{description_legend:hide},description'
	. ';{options_legend},bbit_iso_coa_options,mandatory'
	. ';{config_legend},bbit_iso_coa_template,bbit_iso_coa_feInput'
// 	. ';{search_filters_legend},fe_filter,fe_sorting,be_filter'
	;

$GLOBALS['TL_DCA']['tl_iso_attributes']['subpalettes']['bbit_iso_coa_feInput_bbit_iso_coa_checkbox']
	= 'bbit_iso_coa_embedPrice'
	. ',bbit_iso_coa_hideZeroPrices,bbit_iso_coa_hideCurrentPrice';
$GLOBALS['TL_DCA']['tl_iso_attributes']['subpalettes']['bbit_iso_coa_feInput_bbit_iso_coa_radio']
	= 'bbit_iso_coa_embedPrice,bbit_iso_coa_displayDifference'
	. ',bbit_iso_coa_hideZeroPrices,bbit_iso_coa_hideCurrentPrice';
$GLOBALS['TL_DCA']['tl_iso_attributes']['subpalettes']['bbit_iso_coa_feInput_bbit_iso_coa_select']
	= 'bbit_iso_coa_blankOption,bbit_iso_coa_blankLabel'
	. ',bbit_iso_coa_embedPrice,bbit_iso_coa_displayDifference'
	. ',bbit_iso_coa_hideZeroPrices,bbit_iso_coa_hideCurrentPrice';
$GLOBALS['TL_DCA']['tl_iso_attributes']['subpalettes']['bbit_iso_coa_feInput_bbit_iso_coa_selectMultiple']
	= 'size'
	. ',bbit_iso_coa_embedPrice'
	. ',bbit_iso_coa_hideZeroPrices,bbit_iso_coa_hideCurrentPrice';
	
$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['type']['options'][] = 'bbit_iso_coa';
		
$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['bbit_iso_coa_options'] = array(
	'label'			=> &$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_options'],
	'exclude' 		=> true,
	'inputType' 	=> 'multiColumnWizard',
	'eval' 			=> array(
		'columnFields' => array(
			'image' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_coa_image'],
				'exclude'	=> true,
				'inputType'	=> 'filepicker4ward',
				'eval'		=> array(
					'extensions'=>'png,jpg,jpeg,gif',
					'style'		=> 'width:100px;',
				),
			),
			'value' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_coa_value'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array(
					'style'		=> 'width:80px;',
				),
			),
			'label' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_coa_label'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array(
					'mandatory'	=> true,
					'style'		=> 'width:230px;',
				),
			),
			'priceDefault' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_coa_priceDefault'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array(
					'mandatory'	=> true,
					'rgxp'		=> 'price',
					'style'		=> 'width:90px;',
				),
				'save_callback' => array(
					array('IsotopeChargedOptionAttribute', 'callbackFormatPrice'),
				),
			),
			'default' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_coa_default'],
				'exclude'	=> true,
				'inputType'	=> 'checkbox',
				'eval'		=> array(
					'style'		=> 'width:auto;',
				),
			),
			'group' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_coa_group'],
				'exclude'	=> true,
				'inputType'	=> 'checkbox',
				'eval'		=> array(
					'style'		=> 'width:auto;',
				),
			),
		)
	),
	'load_callback'	=> array(
		array('IsotopeChargedOptionAttribute', 'callbackLoadCOAOptions'),
	),
	'save_callback'	=> array(
		array('IsotopeChargedOptionAttribute', 'callbackSaveCOAOptions'),
	),
);

$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['bbit_iso_coa_template'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_template'],
	'exclude'	=> true,
	'inputType'	=> 'select',
	'options'	=> IsotopeBackend::getTemplates('form_bbit_iso_coa'),
	'eval'		=> array(
		'includeBlankOption'=> true,
		'tl_class'	=> 'clr w50',
	),
);

$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['bbit_iso_coa_feInput'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_feInput'],
	'exclude'	=> true,
	'inputType'	=> 'select',
	'default'	=> 'bbit_iso_coa_radio',
	'options'	=> array('bbit_iso_coa_radio', 'bbit_iso_coa_checkbox', 'bbit_iso_coa_select', 'bbit_iso_coa_selectMultiple'),
	'reference'	=> &$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_feInputOptions'],
	'eval'		=> array(
		'submitOnChange'=> true,
		'tl_class'	=> 'w50',
	),
);

$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['bbit_iso_coa_blankOption'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_blankOption'],
	'exclude'	=> true,
	'default'	=> 1,
	'inputType'	=> 'checkbox',
	'eval'		=> array(
		'tl_class'	=> 'clr w50 cbx m12',
	),
);

$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['bbit_iso_coa_blankLabel'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_blankLabel'],
	'exclude'	=> true,
	'default'	=> '-',
	'inputType'	=> 'text',
	'eval'		=> array(
		'tl_class'	=> 'w50',
	),
);

$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['bbit_iso_coa_embedPrice'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_embedPrice'],
	'exclude'	=> true,
	'default'	=> '&nbsp;(%s)',
	'inputType'	=> 'text',
	'eval'		=> array(
		'tl_class'	=> 'clr w50',
	),
);

$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['bbit_iso_coa_displayDifference'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_displayDifference'],
	'exclude'	=> true,
	'default'	=> 1,
	'inputType'	=> 'checkbox',
	'eval'		=> array(
		'tl_class'	=> 'w50 cbx m12',
	),
);

$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['bbit_iso_coa_hideZeroPrices'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_hideZeroPrices'],
	'exclude'	=> true,
	'default'	=> 1,
	'inputType'	=> 'checkbox',
	'eval'		=> array(
		'tl_class'	=> 'clr w50 cbx',
	),
);

$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['bbit_iso_coa_hideCurrentPrice'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_hideCurrentPrice'],
	'exclude'	=> true,
	'default'	=> 1,
	'inputType'	=> 'checkbox',
	'eval'		=> array(
		'tl_class'	=> 'w50 cbx',
	),
);
