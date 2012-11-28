-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

CREATE TABLE `tl_iso_attributes` (

  `bbit_iso_coa_options` blob NULL,
  `bbit_iso_coa_template` varchar(255) NOT NULL default '',
  `bbit_iso_coa_feInput` varchar(255) NOT NULL default '',
  `bbit_iso_coa_embedPrice` varchar(255) NOT NULL default '',
  `bbit_iso_coa_displayDifference` char(1) NOT NULL default '',
  `bbit_iso_coa_hideZeroPrices` char(1) NOT NULL default '',
  `bbit_iso_coa_hideCurrentPrice` char(1) NOT NULL default '',
  
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

