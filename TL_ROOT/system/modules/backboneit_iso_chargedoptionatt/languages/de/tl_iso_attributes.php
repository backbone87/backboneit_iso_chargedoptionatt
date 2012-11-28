<?php

$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_options']
	= array('Optionen', 'Die möglichen Optionen (Wertebereich) für dieses Attribut definieren. Welche Optionen für ein individuelles Produkt tatsächlich verfügbar sind, werden beim jeweiligen Produkt festgelegt. Zur Anzeige der Preise im Frontend, muss der Platzhalter "%s" innerhalb der Options-Bezeichnung verwendet werden.');

$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_pricesPerProduct']
	= array('Preise je Produkt festlegen', 'Ermöglicht die Preise der einzelnen Optionen für jedes Produkt individuell festzulegen.');

$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_feInput']
	= array('Auswahltyp', 'Wie die Option vom Benutzer im Frontend festgelegt werden kann.');
$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_feInputOptions'] = array(
	'bbit_iso_coa_radioClean'	=> 'Radio (Einzelauswahl)',
	'bbit_iso_coa_checkbox'		=> 'Checkbox (Mehrfachauswahl)',
	'bbit_iso_coa_select'		=> 'Select (Einzelauswahl)',
	'bbit_iso_coa_selectMultiple'=> 'Select (Mehrfachauswahl)',
);

$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_embedPrice']
	= array('Preis einbetten', 'Den Optionspreis in eine Zeichenkette einbetten. "%s" wird mit dem Preis ersetzt. Damit diese Einstellung im Frontend sichtbar ist, muss außerdem ein Platzhalter "%s" in den Options-Bezeichnungen eingesetzt werden.');
	
$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_displayDifference']
	= array('Preisunterschiede anzeigen', 'Zeigt die Preisunterschiede der nicht gewählten Optionen gegenüber der ausgewählten Option an. Damit diese Einstellung im Frontend sichtbar ist, muss außerdem ein Platzhalter "%s" in den Options-Bezeichnungen eingesetzt werden.');
	
$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_hideZeroPrices']
	= array('"Null"-Preise nicht anzeigen', 'Gibt Preise, derren Betrag 0 ist, nicht aus. Damit diese Einstellung im Frontend sichtbar ist, muss außerdem ein Platzhalter "%s" in den Options-Bezeichnungen eingesetzt werden.');
	
$GLOBALS['TL_LANG']['tl_iso_attributes']['bbit_iso_coa_hideCurrentPrice']
	= array('Preis der gewählten Option verstecken', 'Gibt den Preis der aktuell gewählten Option(en) nicht mit aus. Damit diese Einstellung im Frontend sichtbar ist, muss außerdem ein Platzhalter "%s" in den Options-Bezeichnungen eingesetzt werden.');
