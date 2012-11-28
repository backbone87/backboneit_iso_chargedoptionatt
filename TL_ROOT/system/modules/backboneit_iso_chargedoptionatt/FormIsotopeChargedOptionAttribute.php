<?php

class FormIsotopeChargedOptionAttribute extends Widget {

	protected $strType = 'bbit_iso_coa_select';
	
	protected $strTemplate = '';

	protected $arrOptions = array();
	
	protected $strError = '';

	public function __set($strKey, $varValue) {
		switch($strKey) {
			case 'bbit_iso_coa_feInput':
				$this->strType = $varValue;
				switch($varValue) {
					default:
						$this->strType = 'bbit_iso_coa_select';
					case 'bbit_iso_coa_select':
					case 'bbit_iso_coa_radio':
						unset($this->arrAttributes['multiple']);
						break;
						
					case 'bbit_iso_coa_selectMultiple':
					case 'bbit_iso_coa_checkbox':
						$this->arrAttributes['multiple'] = 'multiple';
						break;
				}
				
			case 'multiple':
				break;
				
			case 'template':
				$this->strTemplate = $varValue;
				break;
				
			case 'mandatory':
				if($varValue) {
					$this->arrAttributes['required'] = 'required';
				} else {
					unset($this->arrAttributes['required']);
				}
				parent::__set($strKey, $varValue);
				break;

			case 'mSize':
				if($this->multiple) {
					$this->arrAttributes['size'] = $varValue;
				}
				break;

			case 'options':
				$this->arrOptions = deserialize($varValue);
				break;

			case 'rgxp':
				// Ignore
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}

	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'options':
				return $this->arrOptions;
				break;

			default:
				return parent::__get($strKey);
				break;
		}
	}
	
	public function submitInput() {
		return isset($_POST[$this->strName]);
	}

	public function validate() {
		if(!$this->submitInput()) {
			return;
		}
		
		$arrInput = deserialize($this->getPost($this->strName), true);
		
		foreach($arrInput as $strInput) {
			foreach($this->arrOptions as $arrOption) {
				if($strInput == $arrOption['value']) {
					$varValue[] = $strInput;
					if(!$this->multiple) {
						break 2;
					}
				}
			}
		}
		
		$this->multiple || $varValue = $varValue[0];
		
		if($this->mandatory && !$varValue) {
			strlen($this->strLabel)
				? $this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory'], $this->strLabel))
				: $this->addError($GLOBALS['TL_LANG']['ERR']['mdtryNoLabel']);
				
			$this->class = 'error';
		}
		
		$this->varValue = $varValue;
	}
	
	public function parse($arrAttributes=null) {
		$this->addAttributes($arrAttributes);
		return $this->generateWithError();
	}
	
	public function generateWithError($blnSwitchOrder = false) {
		$this->strError = $this->getErrorAsHTML();
		return $this->generate();
	}
	
	public function generate() {
		$varValue = !$this->multiple && is_array($this->varValue)
			? $this->varValue[0]
			: $this->varValue;
		
		$strTemplate = strlen($this->strTemplate) ? $this->strTemplate : 'form_bbit_iso_coa';
		ob_start();
		include $this->getTemplate($strTemplate, $this->strFormat);
		return ob_get_clean();
	}
	
}
