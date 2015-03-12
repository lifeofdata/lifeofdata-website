<?php
class installerDbUpdaterCsp {
	static public function runUpdate() {
		self::update_004();
		self::update_006();
		self::update_013();
		self::update_017();
		self::update_023();
		self::update_027();
	}
	static public function update_004() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix;
		dbCsp::query("INSERT INTO `". $wpPrefix.CSP_DB_PREF. "modules` ( id, code, active, type_id, params, has_tab, label, description, ex_plug_dir) 
		  				VALUES (NULL, 'access', 1, 1, '', 0, 'Access', 'Access', NULL);");
		
		if (!dbCsp::exist($wpPrefix.CSP_DB_PREF. "access")) {
			$q = "CREATE TABLE `". $wpPrefix.CSP_DB_PREF. "access` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`access` varchar(255) DEFAULT NULL,
					`type_access` tinyint(1) DEFAULT NULL,
					PRIMARY KEY (`id`)
				  ) DEFAULT CHARSET=utf8;";
			 dbDelta($q);
		}
		dbCsp::query("INSERT INTO `". $wpPrefix.CSP_DB_PREF. "access` (id, access, type_access) VALUES (1, '10', 3);");
		
		dbCsp::query("INSERT INTO `". $wpPrefix.CSP_DB_PREF. "options` (`id`,`code`,`value`,`label`,`description`,`htmltype_id`,`params`,`cat_id`,`sort_order`) VALUES 
					(NULL,'sub_enter_email_msg','Enter your email to subscribe','\"Enter Email\" message','\"Enter Email\" message',1,'',3,0),
					(NULL,'sub_success_msg','Thank you for subscription!','Subscribe success message','Subscribe success message',1,'',3,0),
					
					(NULL,'soc_yt_enable_link','','Youtube','Youtube',1,'',18,0),
					(NULL,'soc_yt_account','','Youtube','Youtube',1,'',18,0),
					(NULL,'soc_yt_enable_subscribe','','Youtube','Youtube',1,'',18,0),
					(NULL,'soc_yt_sub_layout','default','Youtube','Youtube',1,'',18,0),
					(NULL,'soc_yt_sub_theme','default','Youtube','Youtube',1,'',18,0),
					
					(NULL,'soc_im_enable_link','','Instagram','Instagram',1,'',18,0),
					(NULL,'soc_im_account','','Instagram','Instagram',1,'',18,0);");
	}
	static public function update_006() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix;
		dbCsp::query("INSERT INTO `". $wpPrefix.CSP_DB_PREF. "modules` ( id, code, active, type_id, params, has_tab, label, description, ex_plug_dir) 
		  				VALUES (NULL, 'meta_info', 1, 1, '', 0, 'Meta Info', 'Meta Info', NULL);");
		dbCsp::query("INSERT INTO `". $wpPrefix.CSP_DB_PREF. "options` (`id`,`code`,`value`,`label`,`description`,`htmltype_id`,`params`,`cat_id`,`sort_order`) VALUES 
					(NULL,'meta_title','Site is under construction','Meta title','Meta title',1,'',3,0),
					(NULL,'meta_description','','Meta description','Meta description',1,'',3,0),
					(NULL,'meta_keywords','','Meta keywords','Meta keywords',1,'',3,0);");
		dbCsp::query("INSERT INTO `". $wpPrefix.CSP_DB_PREF. "options` (`id`,`code`,`value`,`label`,`description`,`htmltype_id`,`params`,`cat_id`,`sort_order`) VALUES 
					(NULL,'favico','','Favico','Favico for site',1,'',3,0),
					(NULL,'google_analitics','','Google Analitics','Google Analitics code for site',1,'',3,0);");
	}
	
	static public function update_013() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix;
		dbCsp::query("INSERT INTO `". $wpPrefix.CSP_DB_PREF. "modules` ( id, code, active, type_id, params, has_tab, label, description, ex_plug_dir) 
		  				VALUES (NULL, 'promo_ready', 1, 1, '', 0, 'Promo Ready', 'Promo Ready', NULL);");
	}
	
	static public function update_017() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix;
		dbCsp::query("INSERT INTO `". $wpPrefix.CSP_DB_PREF. "options` (`id`,`code`,`value`,`label`,`description`,`htmltype_id`,`params`,`cat_id`,`sort_order`) VALUES 
					(NULL,'sub_checked_notify','1','Subscribe is checked by default','Subscribe is checked by default',1,'',3,0);");
	}
	static public function update_023() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix;
		dbCsp::query("UPDATE `". $wpPrefix.CSP_DB_PREF. "options` SET htmltype_id = 18 WHERE code = 'sub_notif_end_maint' LIMIT 1");
		dbCsp::query("INSERT INTO `". $wpPrefix.CSP_DB_PREF. "options` (`id`,`code`,`value`,`label`,`description`,`htmltype_id`,`params`,`cat_id`,`sort_order`) VALUES 
					(NULL,'sub_name_enable','1','Enable Subscriber Name Field','Enable Subscribe Name',1,'',3,0);");
	}
	static public function update_027() {
		dbCsp::query("INSERT INTO `@__modules_type` VALUES (8,'subscribe');");
		dbCsp::query("INSERT INTO `@__options` (`id`,`code`,`value`,`label`,`description`,`htmltype_id`,`params`,`cat_id`,`sort_order`) VALUES 
					(NULL,'sub_synchronize_system','','Choose synchronize system','Choose synchronize system',1,'',2,0);");
		
	}
}