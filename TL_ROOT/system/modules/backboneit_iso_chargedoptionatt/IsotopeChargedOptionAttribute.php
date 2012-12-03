<?php

class IsotopeChargedOptionAttribute extends Controller {
	
	const INSERT_TAG_PRICE_DIFFERENCE = 'bbit_iso_coa_price';
	
	public function hookProductAttributes(&$arrAttributes, &$arrVariantAttributes, $objProduct) {
		$arrVariantAttributes = array_flip($arrVariantAttributes);
		
		foreach(array_filter($arrAttributes, array($this, 'isChargedOptionAttribute')) as $strAttrName) {
// 			$arrVariantAttributes[$strAttrName] = true;
			$arrVariantAttributes['price'] = true;
		}
		
		$arrVariantAttributes = array_keys($arrVariantAttributes);
	}
	
	public function hookCalculatePrice($fltPrice, $objSource, $strField, $intTaxClass) {
		if(!($objSource instanceof IsotopeProduct)) {
			return $fltPrice;
		}

		$arrCOAs = array_filter(array_keys($objSource->getAttributes()), array($this, 'isChargedOptionAttribute'));
		$arrProductOptions = $objSource->getOptions(true);
		
		foreach($arrCOAs as $strAttrName) {
			$arrOptions = deserialize($GLOBALS['TL_DCA']['tl_iso_products']['fields'][$strAttrName]['attributes']['bbit_iso_coa_options'], true);
			$arrOptions = $this->mergeCOAOptions($arrOptions, deserialize($objSource->$strAttrName, true), true);
			
			foreach((array) $arrProductOptions[$strAttrName] as $strOption) {
				if($arrOptions[$strOption]['available']) {
					$fltPrice += floatval($arrOptions[$strOption]['price']);
				}
			}
		}
		
		return $fltPrice;
	}
	
	public function hookReplaceInsertTags($strTag) {
		list($strTag, $strProductKey) = explode('::', $strTag, 2);
		if($strTag != self::INSERT_TAG_PRICE_DIFFERENCE) {
			return false;
		}
		
		list($strProductKey, $strArgs) = explode('?', $strProductKey, 2);
		$objProduct = $this->arrProducts[$strProductKey];
		if(!$objProduct) {
			return '';
		}
		
		parse_str($strArgs, $arrArgs);
		$arrOptions = $this->arrOptions[$strProductKey][$arrArgs['f']];
		if(!$arrOptions) {
			return '';
		}
		
		$arrCurrentValue = $objProduct->getOptions(true);
		$arrCurrentValue = array_flip((array) $arrCurrentValue[$arrArgs['f']]);
		
		if($arrArgs['c'] && isset($arrCurrentValue[$arrArgs['v']])) {
			return '';
			
		} elseif(!$arrArgs['d'] || !count($arrCurrentValue)) {
			$fltPrice = $arrOptions[$arrArgs['v']]['price'];
			$blnSign = true;
			
		} elseif(isset($arrCurrentValue[$arrArgs['v']])) {
			$fltPrice = $arrOptions[$arrArgs['v']]['price'];
			$blnSign = false;
			
		} else {
			reset($arrCurrentValue);
			list($strCurrentValue, $_) = each($arrCurrentValue);
			$fltPrice = $arrOptions[$arrArgs['v']]['price'] - $arrOptions[$strCurrentValue]['price'];
			$blnSign = true;
		}
		
		if($arrArgs['z'] && $fltPrice == 0) {
			return '';
		}
		
		$strPrice = $this->Isotope->formatPriceWithCurrency($fltPrice, $arrArgs['h']);
		return sprintf($arrArgs['e'], ($blnSign && $fltPrice > 0 ? '+' : '') . $strPrice);
	}
	
	public function isChargedOptionAttribute($strAttrName) {
		return 'bbit_iso_coa' == $GLOBALS['TL_DCA']['tl_iso_products']['fields'][$strAttrName]['attributes']['type'];
	}
	
	public function callbackCOA($strField, $arrData, $objProduct = null) {
		if(TL_MODE == 'FE') {
			$arrData['attributes']['customer_defined'] = true;
			$arrData['attributes']['ajax_option'] = true;
		} else {
			unset(
				$arrData['attributes']['customer_defined'],
				$arrData['attributes']['ajax_option'],
				$arrData['attributes']['variant_option']
			);
		}
		
		// config the attribute for customer option selection
		// product may only be supplied when product option widget is required
		if($objProduct) {
			return $this->generateAsProductOptionWidget($strField, $arrData, $objProduct);
			
		// config the attribute for DCA
		// used for label generation
		// and in backend product configuration
		} else {
			return $this->generateAsDCA($arrData);
		}
	}
	
	protected function generateAsProductOptionWidget($strField, $arrData, $objProduct) {
		$strFEInput = $arrData['attributes']['bbit_iso_coa_feInput'];
		
		// derived flags
		$blnHTMLLabels = 0 !== strncmp($strFEInput, 'bbit_iso_coa_select', 19);
		$blnSingleSelect = $strFEInput == 'bbit_iso_coa_radio'
			|| $strFEInput == 'bbit_iso_coa_select';
		$blnUseInsertTag = $arrData['attributes']['bbit_iso_coa_hideCurrentPrice']
			|| ($blnSingleSelect && $arrData['attributes']['bbit_iso_coa_displayDifference']);
			
		$strEmbed = $arrData['attributes']['bbit_iso_coa_embedPrice'];
		$strEmbed || $strEmbed = '&nbsp;(%s)';
		
		if($strFEInput == 'bbit_iso_coa_select') {
			$arrData['eval']['includeBlankOption'] = $arrData['attributes']['bbit_iso_coa_blankOption'];
			$arrData['eval']['blankOptionLabel'] = $arrData['attributes']['bbit_iso_coa_blankLabel'];
		}
		
		// merge product specific option configuration with attribute configuration
		$arrOptions = deserialize($arrData['attributes']['bbit_iso_coa_options'], true);
		$arrProductOptions = deserialize($objProduct->$strField, true);
		$arrOptions = $this->mergeCOAOptions($arrOptions, $arrProductOptions, true);
		$arrData['eval']['bbit_iso_coa_options'] = $arrOptions;
		
		$blnUseInsertTag && $strProductKey = $this->cacheProduct($objProduct, $strField, $arrOptions);
		
		foreach($arrOptions as $strValue => $arrOption) {
			if(!$arrOption['available']) {
				continue;
			}
			
			if($blnUseInsertTag) {
				$strPrice = sprintf('{{%s::%s?%s}}',
					self::INSERT_TAG_PRICE_DIFFERENCE,
					$strProductKey,
					http_build_query(array(
						'f' => $strField,
						'v'	=> $strValue,
						'h'	=> $blnHTMLLabels,
						'c' => $arrData['attributes']['bbit_iso_coa_hideCurrentPrice'],
						'd' => $blnSingleSelect && $arrData['attributes']['bbit_iso_coa_displayDifference'],
						'z' => $arrData['attributes']['bbit_iso_coa_hideZeroPrices'],
						'e' => $strEmbed
					))
				);
				
			} elseif($arrData['attributes']['bbit_iso_coa_hideZeroPrices'] && $arrOption['price'] == 0) {
				$strPrice = '';
				
			} else {
				$strPrice = $this->Isotope->formatPriceWithCurrency($arrOption['price'], $blnHTMLLabels);
				$strPrice = sprintf($strEmbed, ($arrOption['price'] > 0 ? '+' : '') . $strPrice);
			}
			
			$arrOption['default'] && $arrData['default'][] = $strValue;
			$arrData['options'][] = $strValue;
			$arrData['reference'][$strValue] = sprintf($arrOption['label'], $strPrice);
		}
		
		$blnSingleSelect && $arrData['default'] = $arrData['default'][0];
		// set the default option (gets overwritten inside IsotopeProduct,
		// if there is a custom selection
		$arrProductOptions = $objProduct->getOptions(true);
		$arrProductOptions[$strField] = $arrData['default'];
		$objProduct->setOptions($arrProductOptions);
		
		// we do not need a callback in FE
		unset(
			$arrData['load_callback'],
			$arrData['save_callback']
		);
		
		return $arrData;
	}
	
	protected function generateAsDCA($arrData) {
		$arrData['eval']['buttons'] = array('copy' => false, 'delete' => false);
		$arrData['eval']['columnFields'] = array(
			'value' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_coa_value'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array(
					'readonly'	=> true,
					'mandatory' => true,
					'style'		=> 'width:100px;',
				),
			),
			'label' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_coa_label'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array(
					'readonly'	=> true,
					'mandatory'	=> true,
					'style'		=> 'width:250px;',
				),
			),
			'price' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_coa_price'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array(
					'rgxp'		=> 'price',
					'style'		=> 'width:80px;',
				),
				'save_callback' => array(
					array('IsotopeChargedOptionAttribute', 'callbackFormatPrice')
				),
			),
			'priceDefault' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_coa_priceDefault'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array(
					'readonly'	=> true,
					'mandatory'	=> true,
					'rgxp'		=> 'price',
					'style'		=> 'width:80px;',
				),
				'save_callback' => array(
					array('IsotopeChargedOptionAttribute', 'callbackFormatPrice')
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
			'available' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_coa_available'],
				'exclude'	=> true,
				'inputType'	=> 'checkbox',
				'eval'		=> array(
					'style'		=> 'width:auto;',
				),
			),
		);
		
		unset(
			$arrData['options'],
			$arrData['reference']
		);
		
		foreach(deserialize($arrData['attributes']['bbit_iso_coa_options'], true) as $strValue => $arrOption) {
			$arrData['reference'][$strValue] = sprintf($arrOption['label'], '');
		}
		
		$arrData['load_callback'][] = array('IsotopeChargedOptionAttribute', 'callbackLoadCOA');
		$arrData['save_callback'][] = array('IsotopeChargedOptionAttribute', 'callbackSaveCOA');
		
		return $arrData;
	}
	
	/**
	 * Merge the options of product configuration and of attribute configuration
	 * and prepare it for MCW.
	 * 
	 * @param array|serialized $varValue The options of product configuration from DB.
	 * @param DataContainer $objDC The current DC.
	 * @return array The MCW options array.
	 */
	public function callbackLoadCOA($arrProductOptions, $objDC) {
		$arrOptions = $GLOBALS['TL_DCA']['tl_iso_products']['fields'][$objDC->field]['attributes']['bbit_iso_coa_options'];
		$arrOptions = deserialize($arrOptions, true);
		$arrProductOptions = deserialize($arrProductOptions, true);
		return array_values($this->mergeCOAOptions($arrOptions, $arrProductOptions));
	}
	
	/**
	 * Transforms the MCW return to the product specific options.
	 * 
	 * @param array|serialized $varValue The MCW return.
	 * @param DataContainer $objDC The current DC.
	 * @return array The options array for DB.
	 */
	public function callbackSaveCOA($varValue, $objDC) {
		$arrProductOptions = array();
		$arrKeys = array('available' => '', 'default' => '', 'price' => '');
		
		foreach(deserialize($varValue, true) as $arrOption) {
			$strValue = $arrOption['value'];
			$arrProductOptions[$strValue] = array_intersect_key($arrOption, $arrKeys);
		}
		
		return $arrProductOptions;
	}
	
	/**
	 * Prepared the options stored in DB for the MCW, which only handles indexed
	 * arrays.
	 * 
	 * @param array|serialized $varValue The options from DB.
	 * @param DataContainer $objDC The current DC.
	 * @return array The options prepared for MCW.
	 */
	public function callbackLoadCOAOptions($varValue, $objDC) {
		return array_values(deserialize($varValue, true));
	}
	
	/**
	 * Checks and transforms the given options array from attribute
	 * configuration.
	 * 
	 * @param array|serialized $varValue The options array
	 * @param DataContainer $objDC
	 * @return array The options to store to DB.
	 */
	public function callbackSaveCOAOptions($varValue, $objDC) {
		$varValue = deserialize($varValue, true);
		$arrOptions = array();
		
		foreach($varValue as $arrOption) {
			$arrOptions[$arrOption['value']] = $arrOption;
		}
			
		if(count($arrOptions) != count($varValue)) {
			throw new Exception('Values must be unique');
		}
			
		return $arrOptions;
	}

	public function callbackFormatPrice($varValue) {
		return strlen($varValue) ? sprintf("%01.2f", $varValue) : '';
	}
	
	/**
	 * Merges the options from attribute configuration with the options from
	 * the product/variant configuration.
	 * 
	 * @param array $arrOptions The options from attribute configuration.
	 * @param array $arrProductOptions The options from product/variant configuration.
	 * @param boolean $blnPricesPerProduct Whether or not individual prices for each product are activated.
	 * @return array The merged options.
	 */
	protected function mergeCOAOptions(array $arrOptions, array $arrProductOptions, $blnPrice = false) {
		$arrProductOptions = array_intersect_key($arrProductOptions, $arrOptions);
		
		foreach($arrOptions as $strValue => $arrRow) {
			$arrProductOptions[$strValue] && $arrRow = array_merge($arrRow, $arrProductOptions[$strValue]);
			if($blnPrice) {
				strlen($arrRow['price']) || $arrRow['price'] = $arrRow['priceDefault'];
			}
			$arrProductOptions[$strValue] = $arrRow;
		}
		
		return $arrProductOptions;
	}
	
	protected $arrProducts = array();
	
	protected $arrOptions = array();
	
	protected function cacheProduct(IsotopeProduct $objProduct, $strField, array $arrOptions) {
		$this->arrProducts[$objProduct->id] = $objProduct;
		$this->arrOptions[$objProduct->id][$strField] = $arrOptions;
		return $objProduct->id;
	}
	
	protected function __construct() {
		parent::__construct();
		$this->import('Isotope');
	}
	
	protected function __clone() {
	}
	
	private static $objInstance;
	
	public static function getInstance() {
		if(!isset(self::$objInstance)) {
			self::$objInstance = new self();
		}
			
		return self::$objInstance;
	}
	
}
