<?php

class IsotopeChargedOptionAttributeWM extends IsotopeChargedOptionAttribute {
	
	protected function generateAsDCA($arrData) {
		$arrData = parent::generateAsDCA($arrData);
		
		unset($arrData['eval']['columnFields']['label']); // hide default label
		unset($arrData['eval']['buttons']); // allow access to all buttons
		$arrData['eval']['columnFields']['value']['eval']['readonly'] = false;
		$arrData['eval']['columnFields']['value']['eval']['style'] = 'width:300px;';
		
		$arrData['load_callback']['bbit_iso_coa'][0] .= 'WM';
		$arrData['save_callback']['bbit_iso_coa'][0] .= 'WM';
		
		return $arrData;
	}
	
	public function callbackSaveCOA($varValue, $objDC) {
		$arrProductOptions = parent::callbackSaveCOA($varValue, $objDC);
		$arrAttributeOptions = $GLOBALS['TL_DCA']['tl_iso_products']['fields'][$objDC->field]['attributes']['bbit_iso_coa_options'];
		$arrAttributeOptions = deserialize($arrAttributeOptions, true);
		
		foreach(deserialize($varValue, true) as $arrOption) {
			$strValue = $arrOption['value'];
			if(!isset($arrAttributeOptions[$strValue])) { // custom option not set in attribute config
				$arrProductOption = &$arrProductOptions[$strValue];
				$arrProductOption['custom'] = true;
				$arrProductOption['label'] = $strValue;
				$arrProductOption['value'] = $strValue;
				$arrProductOption['priceDefault'] = $arrOption['priceDefault'];
				strlen($arrOption['price']) && $arrProductOption['priceDefault'] = $arrOption['price'];
				if(!strlen($arrProductOption['priceDefault'])) {
					throw new Exception('Bitte geben Sie für alle individuellen Optionen einen Preis an!');
				}
			}
		}
		
		// remove placeholders from value, while maintaining order
		$arrCleaned = array();
		foreach($arrProductOptions as $strValue => $arrOption) {
			$arrCleaned[$arrOption['custom'] ? sprintf($strValue, '') : $strValue] = $arrOption;
		}
		return $arrCleaned;
	}
	
	protected function mergeCOAOptions(array $arrAttributeOptions, array $arrProductOptions, $blnPrice = false) {
		$arrMerged = parent::mergeCOAOptions($arrAttributeOptions, $arrProductOptions, $blnPrice);
		
		// only process custom options (not already merged)
		foreach(array_diff_key($arrProductOptions, $arrMerged) as $strValue => $arrOption) {
			$blnPrice && $arrOption['price'] = $arrOption['priceDefault'];
			$arrMerged[$strValue] = $arrOption;
		}
		
		return $arrMerged;
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
