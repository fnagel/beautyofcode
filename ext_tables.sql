#
# Table structure for table 'tx_beautyofcode_domain_model_flexform'
#
CREATE TABLE tx_beautyofcode_domain_model_flexform (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  PRIMARY KEY (uid),
	KEY parent (pid),
);
