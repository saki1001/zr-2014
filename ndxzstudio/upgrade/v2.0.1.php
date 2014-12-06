<?php

class upgrade_201
{
	var $error;
	var $messages;
	var $version = '2.0.1';
	var $charset = 'utf8';
	var $collate = 'utf8_unicode_ci';

	function upgrade()
	{
		$OBJ =& get_instance();
		
		// forcing a change to the users table for user ID = 1 - just in case
		$OBJ->db->updateArray(PX.'users', array('user_admin' => '1'), "ID = '1'");
		
		// need to create the stats table if it didn't happen before
		$this->add_stats();	
	}
	
	function add_stats()
	{
		$OBJ =& get_instance();
		
		$version = preg_replace('/[^0-9.].*/', '', mysql_get_server_info($OBJ->db->link));
		
		if (version_compare($version, '4.1', '>='))
		{
			$isam = 'DEFAULT CHARACTER SET ' . $this->charset;
			$isam .= ' COLLATE ' . $this->collate;
		}
		else
		{
			$isam = '';
		}
		
		// need to create the stats table if it didn't happen before
		$sql = "CREATE TABLE IF NOT EXISTS ".PX."stats (
		  hit_id int(14) NOT NULL AUTO_INCREMENT,
		  hit_addr varchar(16) NOT NULL DEFAULT '',
		  hit_country varchar(30) NOT NULL DEFAULT '',
		  hit_lang varchar(10) NOT NULL DEFAULT '',
		  hit_domain varchar(100) NOT NULL DEFAULT '',
		  hit_referrer varchar(100) NOT NULL DEFAULT '',
		  hit_page varchar(100) NOT NULL DEFAULT '',
		  hit_agent varchar(250) NOT NULL DEFAULT '',
		  hit_keyword varchar(250) NOT NULL DEFAULT '',
		  hit_os varchar(20) NOT NULL DEFAULT '',
		  hit_browser varchar(20) NOT NULL DEFAULT '',
		  hit_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  hit_month varchar(7) NOT NULL DEFAULT '',
		  hit_day date NOT NULL DEFAULT '0000-00-00',
		  PRIMARY KEY (hit_id)
		) $isam ;";
		
		$OBJ->db->query($sql);
	}
}