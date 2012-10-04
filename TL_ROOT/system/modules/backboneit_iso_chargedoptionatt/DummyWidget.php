<?php

class DummyWidget extends Widget {
	
	protected $strTemplate = 'form_widget';
	
	public function __construct($arrAttributes = false) {
		parent::__construct($arrAttributes);
	}
	
	public function generateLabel() {
		return !$this->includeLabel && strlen($this->strLabel) ? '<p>' . $this->strLabel . '</p>' : '';
	}
	
	public function submitInput() {
		return false;
	}
	
	public function validate() {	
	}
	
	public function generate() {
		if($this->includeLabel && strlen($this->strLabel)) {
        	return sprintf('<div id="ctrl_%s" class="dummy_container%s"><div class="label">%s</div><p>%s</p></div>',
        		$this->strId,
        		($this->strClass != '' ? ' ' . $this->strClass : ''),
        		$this->strLabel,
				($this->dummyMessage ? $this->dummyMessage : $GLOBALS['TL_LANG']['MSC']['dummyMessage'])
        	) . $this->addSubmit();
		} else {
	        return sprintf('<div id="ctrl_%s" class="dummy_container%s"><p>%s</p></div>',
	        	$this->strId,
				($this->strClass != '' ? ' ' . $this->strClass : ''),
				($this->dummyMessage ? $this->dummyMessage : $GLOBALS['TL_LANG']['MSC']['dummyMessage'])
			) . $this->addSubmit();
		}
	}
	
}
