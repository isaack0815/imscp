<?php
/**
 * i-MSCP a internet Multi Server Control Panel
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is "VHCS - Virtual Hosting Control System".
 *
 * The Initial Developer of the Original Code is moleSoftware GmbH.
 * Portions created by Initial Developer are Copyright (C) 2001-2006
 * by moleSoftware GmbH. All Rights Reserved.
 *
 * Portions created by the ispCP Team are Copyright (C) 2006-2010 by
 * isp Control Panel. All Rights Reserved.
 *
 * Portions created by the i-MSCP Team are Copyright (C) 2010-2012 by
 * i-MSCP a internet Multi Server Control Panel. All Rights Reserved.
 *
 * @category	i-MSCP
 * @package		iMSCP_Core
 * @subpackage	Reseller
 * @copyright   2001-2006 by moleSoftware GmbH
 * @copyright   2006-2010 by ispCP | http://isp-control.net
 * @copyright   2010-2012 by i-MSCP | http://i-mscp.net
 * @author      ispCP Team
 * @author      i-MSCP Team
 * @link        http://i-mscp.net
 */

// Include core library
require 'imscp-lib.php';

iMSCP_Events_Manager::getInstance()->dispatch(iMSCP_Events::onResellerScriptStart);

check_login(__FILE__);

/**
 * @var $cfg iMSCP_Config_Handler_File
 */
$cfg = iMSCP_Registry::get('config');

/* @var $phpini iMSCP_PHPini */
$phpini = iMSCP_PHPini::getInstance();

$tpl = new iMSCP_pTemplate();
$tpl->define_dynamic(
	array(
		'layout' => 'shared/layouts/ui.tpl',
		'page' => 'reseller/hosting_plan_edit.tpl',
		'page_message' => 'layout',
		'subdomain_edit' => 'page',
		'alias_edit' => 'page',
		'mail_edit' => 'page',
		'ftp_edit' => 'page',
		'sql_db_edit' => 'page',
		'sql_user_edit' => 'page',
		't_software_support' => 'page',
		't_phpini_system' => 'page',
		't_phpini_allow_url_fopen' => 'page',
		't_phpini_display_errors' => 'page',
		't_phpini_disable_functions' => 'page'));

global $hpid;

generateNavigation($tpl);

$tpl->assign(
	array(
		 'THEME_CHARSET' => tr('encoding'),
		 'ISP_LOGO' => layout_getUserLogo(),
		 'TR_HOSTING_PLAN_PROPS' => tr('Hosting plan properties'),
		 'TR_TEMPLATE_NAME' => tr('Template name'),
		 'TR_MAX_SUBDOMAINS' => tr('Max subdomains<br><i>(-1 disabled, 0 unlimited)</i>'),
		 'TR_MAX_ALIASES' => tr('Max aliases<br><i>(-1 disabled, 0 unlimited)</i>'),
		 'TR_MAX_MAILACCOUNTS' => tr('Mail accounts limit<br><i>(-1 disabled, 0 unlimited)</i>'),
		 'TR_MAX_FTP' => tr('FTP accounts limit<br><i>(-1 disabled, 0 unlimited)</i>'),
		 'TR_MAX_SQL' => tr('SQL databases limit<br><i>(-1 disabled, 0 unlimited)</i>'),
		 'TR_MAX_SQL_USERS' => tr('SQL users limit<br><i>(-1 disabled, 0 unlimited)</i>'),
		 'TR_MAX_TRAFFIC' => tr('Traffic limit [MiB]<br><i>(0 unlimited)</i>'),
		 'TR_DISK_LIMIT' => tr('Disk limit [MiB]<br><i>(0 unlimited)</i>'),
		 'TR_SOFTWARE_SUPP' => tr('Softwares installer'),
         'TR_EXTMAIL' => tr('External mail server'),
		 'TR_PHP' => tr('PHP'),
		 'TR_CGI' => tr('CGI'),
		 'TR_DNS' => tr('Custom DNS records'),
		 'TR_BACKUP' => tr('Backup'),
		 'TR_BACKUP_DOMAIN' => tr('Domain'),
		 'TR_BACKUP_SQL' => tr('SQL'),
		 'TR_BACKUP_FULL' => tr('Full'),
		 'TR_BACKUP_NO' => tr('No'),
		 'TR_APACHE_LOGS' => tr('Apache logfiles'),
		 'TR_AWSTATS' => tr('AwStats'),
		 'TR_YES' => tr('yes'),
		 'TR_NO' => tr('no'),
		 'TR_BILLING_PROPS' => tr('Billing Settings'),
		 'TR_PRICE' => tr('Price'),
		 'TR_SETUP_FEE' => tr('Setup fee'),
		 'TR_VALUE' => tr('Currency'),
		 'TR_PAYMENT' => tr('Payment period'),
		 'TR_STATUS' => tr('Available for purchasing'),
		 'TR_TEMPLATE_DESCRIPTON' => tr('Description'),
		 'TR_EXAMPLE' => tr('(e.g. EUR)'),
		 'TR_TOS_PROPS' => tr('Term Of Service'),
		 'TR_TOS_NOTE' => tr('<b>Optional:</b> Leave this field empty if you do not want term of service for this hosting plan.'),
		 'TR_TOS_DESCRIPTION' => tr('Text'),
		 'TR_EDIT_HOSTING_PLAN' => tr('Update plan'),
		 'TR_UPDATE_PLAN' => tr('Update plan'),
		 'TR_PHPINI_SYSTEM' => tr('PHP Editor'),
		 'TR_USER_EDITABLE_EXEC' => tr('Only exec'),
		 'TR_PHPINI_AL_ALLOW_URL_FOPEN' => tr('Can edit the PHP %s directive', true, '<span class="bold">allow_url_fopen</span>'),
		 'TR_PHPINI_AL_DISPLAY_ERRORS' => tr('Can edit the PHP %s directive', true, '<span class="bold">display_errors</span>'),
		 'TR_PHPINI_AL_DISABLE_FUNCTIONS' => tr('Can edit the PHP %s directive', true, '<span class="bold">disable_functions</span>'),
		 'TR_PHPINI_POST_MAX_SIZE' => tr('PHP %s directive', true, '<span class="bold">post_max_size</span>'),
		 'TR_PHPINI_UPLOAD_MAX_FILESIZE' => tr('PHP %s directive', true, '<span class="bold">upload_max_filezize</span>'),
		 'TR_PHPINI_MAX_EXECUTION_TIME' => tr('PHP %s directive', true, '<span class="bold">max_execution_time</span>'),
		 'TR_PHPINI_MAX_INPUT_TIME' => tr('PHP %s directive', true, '<span class="bold">max_input_time</span>'),
		 'TR_PHPINI_MEMORY_LIMIT' => tr('PHP %s directive', true, '<span class="bold">memory_limit</span>'),
		 'TR_MIB' => tr('MiB'),
		 'TR_SEC' => tr('Sec.'),
		 'HOSTING_PLAN_ID' => $hpid));


$phpini->loadRePerm($_SESSION['user_id']); //load Reseller Permission into object

if (isset($_POST['uaction']) && ('add_plan' == $_POST['uaction'])) {
	// Process data
	if (check_data_iscorrect($tpl, $phpini)) { // Save data to db
		save_data_to_db($phpini);
	} else {
		restore_form($tpl, $phpini);
	}
} else {
	// Get hosting plan id that comes for edit
	if (isset($_GET['hpid'])) {
		$hpid = $_GET['hpid'];
	}

	gen_load_ehp_page($tpl, $hpid, $_SESSION['user_id'], $phpini);
	$tpl->assign('MESSAGE', '');
}

if (isset($cfg->HOSTING_PLANS_LEVEL) && $cfg->HOSTING_PLANS_LEVEL === 'reseller') {
	get_reseller_software_permission($tpl, $_SESSION['user_id']);
}


if ($phpini->checkRePerm('phpiniSystem')) { //if reseller has permission to use php.ini feature
	if ($phpini->checkRePerm('phpiniAllowUrlFopen')) {
		$tpl->parse('T_PHPINI_ALLOW_URL_FOPEN', 't_phpini_allow_url_fopen');
	} else {
		$tpl->assign(array('T_PHPINI_ALLOW_URL_FOPEN' => ''));
		$tpl->assign(array('PHPINI_AL_ALLOW_URL_FOPEN_YES' => '', 'PHPINI_AL_ALLOW_URL_FOPEN_NO' => $cfg->HTML_CHECKED));
	}
	if ($phpini->checkRePerm('phpiniDisplayErrors')) {
		$tpl->parse('T_PHPINI_DISPLAY_ERRORS', 't_phpini_display_errors');
	} else {
		$tpl->assign(array('T_PHPINI_DISPLAY_ERRORS' => ''));
		$tpl->assign(array('PHPINI_AL_DISPLAY_ERRORS_YES' => '', 'PHPINI_AL_DISPLAY_ERRORS_NO' => $cfg->HTML_CHECKED));
	}
	if ($phpini->checkRePerm('phpiniDisableFunctions')) {
		$tpl->parse('T_PHPINI_DISABLE_FUNCTIONS', 't_phpini_disable_functions');
	} else {
		$tpl->assign(array('T_PHPINI_DISABLE_FUNCTIONS' => ''));
		$tpl->assign(array('PHPINI_AL_DISABLE_FUNCTIONS_YES' => '',
						  'PHPINI_AL_DISABLE_FUNCTIONS_NO' => $cfg->HTML_CHECKED,
						  'PHPINI_AL_DISABLE_FUNCTIONS_EXEC' => ''));
	}


} else { //if no permission at all
	$tpl->assign(array('T_PHPINI_SYSTEM' => ''));
	$tpl->assign(array('PHPINI_SYSTEM_YES' => '', 'PHPINI_SYSTEM_NO' => $cfg->HTML_CHECKED));
}

generatePageMessage($tpl);

$tpl->parse('LAYOUT_CONTENT', 'page');

iMSCP_Events_Manager::getInstance()->dispatch(iMSCP_Events::onResellerScriptEnd, array('templateEngine' => $tpl));

$tpl->prnt();

/**
 * Restore form on any error
 */
function restore_form($tpl, $phpini)
{

	/**
	 * @var $cfg iMSCP_Config_Handler_File
	 */
	$cfg = iMSCP_Registry::get('config');

	$tpl->assign(
		array(
			 'HP_NAME_VALUE' => clean_input($_POST['hp_name'], true),
			 'HP_DESCRIPTION_VALUE' => clean_input($_POST['hp_description'], true),
			 'TR_MAX_SUB_LIMITS' => clean_input($_POST['hp_sub'], true),
			 'TR_MAX_ALS_VALUES' => clean_input($_POST['hp_als'], true),
			 'HP_MAIL_VALUE' => clean_input($_POST['hp_mail'], true),
			 'HP_FTP_VALUE' => clean_input($_POST['hp_ftp'], true),
			 'HP_SQL_DB_VALUE' => clean_input($_POST['hp_sql_db'], true),
			 'HP_SQL_USER_VALUE' => clean_input($_POST['hp_sql_user'], true),
			 'HP_TRAFF_VALUE' => clean_input($_POST['hp_traff'], true),
			 'HP_TRAFF' => clean_input($_POST['hp_traff'], true),
			 'HP_DISK_VALUE' => clean_input($_POST['hp_disk'], true),
			 'HP_PRICE' => clean_input($_POST['hp_price'], true),
			 'HP_SETUPFEE' => clean_input($_POST['hp_setupfee'], true),
			 'HP_CURRENCY' => clean_input($_POST['hp_currency'], true),
			 'HP_PAYMENT' => clean_input($_POST['hp_payment'], true),
			 'HP_TOS_VALUE' => clean_input($_POST['hp_tos'], true),
             'TR_EXTMAIL_YES' => ($_POST['external_mail'] == '_yes_') ? $cfg->HTML_CHECKED : '',
             'TR_EXTMAIL_NO' => ($_POST['external_mail'] == '_no_') ? $cfg->HTML_CHECKED : '',
			 'TR_PHP_YES' => ($_POST['php'] == '_yes_') ? $cfg->HTML_CHECKED : '',
			 'TR_PHP_NO' => ($_POST['php'] == '_no_') ? $cfg->HTML_CHECKED : '',
			 'TR_CGI_YES' => ($_POST['cgi'] == '_yes_') ? $cfg->HTML_CHECKED : '',
			 'TR_CGI_NO' => ($_POST['cgi'] == '_no_') ? $cfg->HTML_CHECKED : '',
			 'TR_DNS_YES' => ($_POST['dns'] == '_yes_') ? $cfg->HTML_CHECKED : '',
			 'TR_DNS_NO' => ($_POST['dns'] == '_no_') ? $cfg->HTML_CHECKED : '',
			 'VL_BACKUPD' => ($_POST['backup'] == '_dmn_') ? $cfg->HTML_CHECKED : '',
			 'VL_BACKUPS' => ($_POST['backup'] == '_sql_') ? $cfg->HTML_CHECKED : '',
			 'VL_BACKUPF' => ($_POST['backup'] == '_full_') ? $cfg->HTML_CHECKED
				 : '',
			 'VL_BACKUPN' => ($_POST['backup'] == '_no_') ? $cfg->HTML_CHECKED : '',
			 'TR_STATUS_YES' => ($_POST['status']) ? $cfg->HTML_CHECKED : '',
			 'TR_STATUS_NO' => (!$_POST['status']) ? $cfg->HTML_CHECKED : '',
			 'TR_SOFTWARE_YES' => ($_POST['software_allowed'] == '_yes_')
				 ? $cfg->HTML_CHECKED : '',
			 'TR_SOFTWARE_NO' => ($_POST['software_allowed'] == '_no_')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_SYSTEM_YES' => ($phpini->getClPermVal('phpiniSystem') == 'yes')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_SYSTEM_NO' => ($phpini->getClPermVal('phpiniSystem') != 'yes')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_AL_ALLOW_URL_FOPEN_YES' => ($phpini->getClPermVal('phpiniAllowUrlFopen') == 'yes')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_AL_ALLOW_URL_FOPEN_NO' => ($phpini->getClPermVal('phpiniAllowUrlFopen') != 'yes')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_AL_DISPLAY_ERRORS_YES' => ($phpini->getClPermVal('phpiniDisplayErrors') == 'yes')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_AL_DISPLAY_ERRORS_NO' => ($phpini->getClPermVal('phpiniDisplayErrors') != 'yes')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_AL_DISABLE_FUNCTIONS_YES' => ($phpini->getClPermVal('phpiniDisableFunctions') == 'yes')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_AL_DISABLE_FUNCTIONS_NO' => ($phpini->getClPermVal('phpiniDisableFunctions') == 'no')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_AL_DISABLE_FUNCTIONS_EXEC' => ($phpini->getClPermVal('phpiniDisableFunctions') == 'exec')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_POST_MAX_SIZE' => $phpini->getDataVal('phpiniPostMaxSize'),
			 'PHPINI_UPLOAD_MAX_FILESIZE' => $phpini->getDataVal('phpiniUploadMaxFileSize'),
			 'PHPINI_MAX_EXECUTION_TIME' => $phpini->getDataVal('phpiniMaxExecutionTime'),
			 'PHPINI_MAX_INPUT_TIME' => $phpini->getDataVal('phpiniMaxInputTime'),
			 'PHPINI_MEMORY_LIMIT' => $phpini->getDataVal('phpiniMemoryLimit')

		)
	);
} // end of function restore_form()


/**
 * Generate load data from sql for requested hosting plan
 */
function gen_load_ehp_page($tpl, $hpid, $admin_id, $phpini)
{
	/**
	 * @var $cfg iMSCP_Config_Handler_File
	 */
	$cfg = iMSCP_Registry::get('config');

	$_SESSION['hpid'] = $hpid;

	if (isset($cfg->HOSTING_PLANS_LEVEL) && $cfg->HOSTING_PLANS_LEVEL === 'admin') {
		$query = "SELECT * FROM `hosting_plans` WHERE `id` = ?";
		$res = exec_query($query, $hpid);

		$readonly = $cfg->HTML_READONLY;
		$disabled = $cfg->HTML_DISABLED;
		$edit_hp = tr('View hosting plan');
		$tpl->assign(
				array(
					 'TR_PAGE_TITLE' => tr('i-MSCP - Reseller / View hosting plan'),
					 'FORM' => ''));

	} else {
		$query = "
			SELECT
				*
			FROM
				`hosting_plans`
			WHERE
				`reseller_id` = ?
			AND
				`id` = ?
		";

		$res = exec_query($query, array($admin_id, $hpid));
		$readonly = '';
		$disabled = '';
		$edit_hp = tr('Edit hosting plan');
		$tpl->assign('TR_PAGE_TITLE', tr('i-MSCP - Reseller / Edit hosting plan'));
	}

	if ($res->rowCount() !== 1) { // Error
		redirectTo('hosting_plan.php');
	}

	$data = $res->fetchRow();
	
	//Do not allow to edit the reseller hosting plans if set to admin hosting plans
	if (isset($cfg->HOSTING_PLANS_LEVEL) && $cfg->HOSTING_PLANS_LEVEL === 'admin') {
		if($data['reseller_id'] == $admin_id) {
			redirectTo('hosting_plan.php');
		}
	}
	
	$props = $data['props'];
	$description = $data['description'];
	$price = $data['price'];
	$setup_fee = $data['setup_fee'];
	$value = $data['value'];
	$payment = $data['payment'];
	$status = $data['status'];
	$tos = $data['tos'];

	list(
		$hp_php, $hp_cgi, $hp_sub, $hp_als, $hp_mail, $hp_ftp, $hp_sql_db,
		$hp_sql_user, $hp_traff, $hp_disk, $hp_backup, $hp_dns, $hp_allowsoftware,
		$phpini_system, $phpini_al_allow_url_fopen, $phpini_al_display_errors, $phpini_al_disable_functions,
		$phpini_post_max_size, $phpini_upload_max_filesize, $phpini_max_execution_time, $phpini_max_input_time, $phpini_memory_limit, $hp_ext_mail
		) = array_pad(explode(';', $props), 24, 'no');

	$hp_name = $data['name'];

	if ($description == '')
		$description = '';

	if ($tos == '') {
		$tos = '';
	}

	if ($payment == '') {
		$payment = '';
	}

	if ($value == '') {
		$value = '';
	}

	list(
		$rsub_max,
		$rals_max,
		$rmail_max,
		$rftp_max,
		$rsql_db_max,
		$rsql_user_max
		) = check_reseller_permissions($_SESSION['user_id'], 'all_permissions');

	if ($rsub_max == "-1") $tpl->assign('ALIAS_EDIT', '');
	if ($rals_max == "-1") $tpl->assign('SUBDOMAIN_EDIT', '');
	if ($rmail_max == "-1") $tpl->assign('MAIL_EDIT', '');
	if ($rftp_max == "-1") $tpl->assign('FTP_EDIT', '');
	if ($rsql_db_max == "-1") $tpl->assign('SQL_DB_EDIT', '');
	if ($rsql_user_max == "-1") $tpl->assign('SQL_USER_EDIT', '');

	$tpl->assign(
		array(
			 'HP_NAME_VALUE' => tohtml($hp_name),
			 'TR_EDIT_HOSTING_PLAN' => tohtml($edit_hp),
			 'HOSTING_PLAN_ID' => tohtml($hpid),
			 'TR_MAX_SUB_LIMITS' => tohtml($hp_sub),
			 'TR_MAX_ALS_VALUES' => tohtml($hp_als),
			 'HP_MAIL_VALUE' => tohtml($hp_mail),
			 'HP_FTP_VALUE' => tohtml($hp_ftp),
			 'HP_SQL_DB_VALUE' => tohtml($hp_sql_db),
			 'HP_SQL_USER_VALUE' => tohtml($hp_sql_user),
			 'HP_TRAFF_VALUE' => tohtml($hp_traff),
			 'HP_DISK_VALUE' => tohtml($hp_disk),
			 'HP_DESCRIPTION_VALUE' => tohtml($description),
			 'HP_PRICE' => tohtml($price),
			 'HP_SETUPFEE' => tohtml($setup_fee),
			 'HP_CURRENCY' => tohtml($value),
			 'READONLY' => tohtml($readonly),
			 'DISABLED' => tohtml($disabled),
			 'HP_PAYMENT' => tohtml($payment),
			 'HP_TOS_VALUE' => tohtml($tos),
             'TR_EXTMAIL_YES' => ($hp_ext_mail == '_yes_') ? $cfg->HTML_CHECKED : '',
             'TR_EXTMAIL_NO' => ($hp_ext_mail == '_no_') ? $cfg->HTML_CHECKED : '',
			 'TR_PHP_YES' => ($hp_php == '_yes_') ? $cfg->HTML_CHECKED : '',
			 'TR_PHP_NO' => ($hp_php == '_no_') ? $cfg->HTML_CHECKED : '',
			 'TR_CGI_YES' => ($hp_cgi == '_yes_') ? $cfg->HTML_CHECKED : '',
			 'TR_CGI_NO' => ($hp_cgi == '_no_') ? $cfg->HTML_CHECKED : '',
			 'TR_DNS_YES' => ($hp_dns == '_yes_') ? $cfg->HTML_CHECKED : '',
			 'TR_DNS_NO' => ($hp_dns == '_no_') ? $cfg->HTML_CHECKED : '',
			 'VL_BACKUPD' => ($hp_backup == '_dmn_') ? $cfg->HTML_CHECKED : '',
			 'VL_BACKUPS' => ($hp_backup == '_sql_') ? $cfg->HTML_CHECKED : '',
			 'VL_BACKUPF' => ($hp_backup == '_full_') ? $cfg->HTML_CHECKED : '',
			 'VL_BACKUPN' => ($hp_backup == '_no_') ? $cfg->HTML_CHECKED : '',
			 'TR_STATUS_YES' => ($status) ? $cfg->HTML_CHECKED : '',
			 'TR_STATUS_NO' => (!$status) ? $cfg->HTML_CHECKED : '',
			 'TR_SOFTWARE_YES' => ($hp_allowsoftware == '_yes_') ? $cfg->HTML_CHECKED
				 : '',
			 'TR_SOFTWARE_NO' => ($hp_allowsoftware == '_no_' || !$hp_allowsoftware)
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_SYSTEM_YES' => ($phpini_system == 'yes') ? $cfg->HTML_CHECKED
				 : '',
			 'PHPINI_SYSTEM_NO' => ($phpini_system != 'yes') ? $cfg->HTML_CHECKED
				 : '',
			 'PHPINI_AL_ALLOW_URL_FOPEN_YES' => ($phpini_al_allow_url_fopen == 'yes')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_AL_ALLOW_URL_FOPEN_NO' => ($phpini_al_allow_url_fopen != 'yes')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_AL_DISPLAY_ERRORS_YES' => ($phpini_al_display_errors == 'yes')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_AL_DISPLAY_ERRORS_NO' => ($phpini_al_display_errors != 'yes')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_AL_DISABLE_FUNCTIONS_YES' => ($phpini_al_disable_functions == 'yes')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_AL_DISABLE_FUNCTIONS_NO' => ($phpini_al_disable_functions == 'no')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_AL_DISABLE_FUNCTIONS_EXEC' => ($phpini_al_disable_functions == 'exec')
				 ? $cfg->HTML_CHECKED : '',
			 'PHPINI_POST_MAX_SIZE' => ($phpini_post_max_size != 'no')
				 ? $phpini_post_max_size : $phpini->getDataVal('phpiniPostMaxSize'),
			 'PHPINI_UPLOAD_MAX_FILESIZE' => ($phpini_upload_max_filesize != 'no')
				 ? $phpini_upload_max_filesize
				 : $phpini->getDataVal('phpiniUploadMaxFileSize'),
			 'PHPINI_MAX_EXECUTION_TIME' => ($phpini_max_execution_time != 'no')
				 ? $phpini_max_execution_time
				 : $phpini->getDataVal('phpiniMaxExecutionTime'),
			 'PHPINI_MAX_INPUT_TIME' => ($phpini_max_input_time != 'no')
				 ? $phpini_max_input_time
				 : $phpini->getDataVal('phpiniMaxInputTime'),
			 'PHPINI_MEMORY_LIMIT' => ($phpini_memory_limit != 'no')
				 ? $phpini_memory_limit : $phpini->getDataVal('phpiniMemoryLimit')
		)
	);
} // end of gen_load_ehp_page()

/**
 * Check correction of input data
 */
function check_data_iscorrect($tpl, $phpini)
{

	global $hp_name, $hp_php, $hp_cgi, $hp_sub, $hp_als, $hp_mail, $hp_ftp, $hp_sql_db, $hp_sql_user, $hp_traff,
$hp_disk, $hpid, $price, $setup_fee, $hp_backup, $hp_dns, $hp_allowsoftware, $hp_ext_mail;


	$ahp_error = array();
	$hp_name = clean_input($_POST['hp_name']);
	$hp_sub = clean_input($_POST['hp_sub']);
	$hp_als = clean_input($_POST['hp_als']);
	$hp_mail = clean_input($_POST['hp_mail']);
	$hp_ftp = clean_input($_POST['hp_ftp']);
	$hp_sql_db = clean_input($_POST['hp_sql_db']);
	$hp_sql_user = clean_input($_POST['hp_sql_user']);
	$hp_traff = clean_input($_POST['hp_traff']);
	$hp_disk = clean_input($_POST['hp_disk']);
	$price = clean_input($_POST['hp_price']);
	$setup_fee = clean_input($_POST['hp_setupfee']);

	if (isset($_SESSION['hpid'])) {
		$hpid = $_SESSION['hpid'];
	} else {
		set_page_message(tr('Undefined reference to data.'), 'error');
	}

	// put hosting plan id into session value
	$_SESSION['hpid'] = $hpid;

	// Get values from previous page and check him correction
    if (isset($_POST['external_mail'])) {
        $hp_ext_mail = $_POST['external_mail'];
    }

    if (isset($_POST['php'])) {
		$hp_php = $_POST['php'];
	}

	if (isset($_POST['cgi'])) {
		$hp_cgi = $_POST['cgi'];
	}

	if (isset($_POST['dns'])) {
		$hp_dns = $_POST['dns'];
	}

	if (isset($_POST['backup'])) {
		$hp_backup = $_POST['backup'];
	}

	if (isset($_POST['software_allowed'])) {
		$hp_allowsoftware = $_POST['software_allowed'];
	} else {
		$hp_allowsoftware = "_no_";
	}

	if ($hp_php == "_no_" && $hp_allowsoftware == "_yes_") {
		set_page_message(tr('The i-MSCP application installer needs PHP to enable it.'), 'error');
	}

	if ($phpini->checkRePerm('phpiniSystem') && isset($_POST['phpini_system'])) {
		$phpini->setClPerm('phpiniSystem', clean_input($_POST['phpini_system']));
	}

	if ($phpini->checkRePerm('phpiniAllowUrlFopen') && isset($_POST['phpini_al_allow_url_fopen'])) {
		$phpini->setClPerm('phpiniAllowUrlFopen', clean_input($_POST['phpini_al_allow_url_fopen']));
	}

	if ($phpini->checkRePerm('phpiniDisplayErrors') && isset($_POST['phpini_al_display_errors'])) {
		$phpini->setClPerm('phpiniDisplayErrors', clean_input($_POST['phpini_al_display_errors']));
	}

	if ($phpini->checkRePerm('phpiniDisplayErrors') && isset($_POST['phpini_al_error_reporting'])) {
		$phpini->setClPerm('phpiniErrorReporting', clean_input($_POST['phpini_al_error_reporting']));
	}

	if ($phpini->checkRePerm('phpiniDisableFunctions') && isset($_POST['phpini_al_disable_functions'])) {
		$phpini->setClPerm('phpiniDisableFunctions', clean_input($_POST['phpini_al_disable_functions']));
	}

	//use phpini->phpiniData as datastore for the following values - should be better in something like hostingPlan class/object later
	if (isset($_POST['phpini_post_max_size']) && (!$phpini->setDataWithPermCheck('phpiniPostMaxSize', $_POST['phpini_post_max_size']))) {
		set_page_message(tr('post_max_size out of range.'), 'error');
	}

	if (isset($_POST['phpini_upload_max_filesize']) && (!$phpini->setDataWithPermCheck('phpiniUploadMaxFileSize', $_POST['phpini_upload_max_filesize']))) {
		set_page_message(tr('upload_max_filesize out of range.'), 'error');
	}

	if (isset($_POST['phpini_max_execution_time']) && (!$phpini->setDataWithPermCheck('phpiniMaxExecutionTime', $_POST['phpini_max_execution_time']))) {
		set_page_message(tr('max_execution_time out of range.'), 'error');
	}

	if (isset($_POST['phpini_max_input_time']) && (!$phpini->setDataWithPermCheck('phpiniMaxInputTime', $_POST['phpini_max_input_time']))) {
		set_page_message(tr('max_input_time out of range.'), 'error');
	}

	if (isset($_POST['phpini_memory_limit']) && (!$phpini->setDataWithPermCheck('phpiniMemoryLimit', $_POST['phpini_memory_limit']))) {
		set_page_message(tr('memory_limit out of range.'), 'error');
	}

	list(
		$rsub_max, $rals_max, $rmail_max, $rftp_max, $rsql_db_max, $rsql_user_max) = check_reseller_permissions(
		$_SESSION['user_id'], 'all_permissions'
	);

	if ($rsub_max == "-1") {
		$hp_sub = "-1";
	} elseif (!imscp_limit_check($hp_sub, -1)) {
		set_page_message(tr('Incorrect subdomains limit.'), 'error');
	}

	if ($rals_max == "-1") {
		$hp_als = "-1";
	} elseif (!imscp_limit_check($hp_als, -1)) {
		set_page_message(tr('Incorrect aliases limit.'), 'error');
	}

	if ($rmail_max == "-1") {
		$hp_mail = "-1";
	} elseif (!imscp_limit_check($hp_mail, -1)) {
		set_page_message(tr('Incorrect mail accounts limit.'), 'error');
	}

	if ($rftp_max == "-1") {
		$hp_ftp = "-1";
	} elseif (!imscp_limit_check($hp_ftp, -1)) {
		set_page_message(tr('Incorrect FTP accounts limit.'), 'error');
	}

	if ($rsql_db_max == "-1") {
		$hp_sql_db = "-1";
	} elseif (!imscp_limit_check($hp_sql_db, -1)) {
		set_page_message(tr('Incorrect SQL users limit.'), 'error');
	} else if ($hp_sql_db == -1 && $hp_sql_user != -1) {
		set_page_message(tr('SQL databases limit is <i>disabled</i>.'), 'error');
	}

	if ($rsql_user_max == "-1") {
		$hp_sql_user = "-1";
	} elseif (!imscp_limit_check($hp_sql_user, -1)) {
		set_page_message(tr('Incorrect SQL databases limit.'), 'error');
	} else if ($hp_sql_user == -1 && $hp_sql_db != -1) {
		set_page_message(tr('SQL users limit is <i>disabled</i>.'), 'error');
	}

	if (!imscp_limit_check($hp_traff, null)) {
		set_page_message(tr('Incorrect traffic limit.'), 'error');
	}

	if (!imscp_limit_check($hp_disk, null)) {
		set_page_message(tr('Incorrect disk quota limit.'), 'error');
	}

	if (!is_numeric($price)) {
		set_page_message(tr('Price must be a number.'), 'error');
	}

	if (!is_numeric($setup_fee)) {
		set_page_message(tr('Setup fee must be a number.'), 'error');
	}

	if (!Zend_Session::namespaceIsset('pageMessages')) {
		return true;
	} else {
		return false;
	}
} // end of check_data_iscorrect()


/**
 * Store new hosting plan.
 *
 * @param iMSCP_PHPini $phpini
 * @return bool
 */
function save_data_to_db($phpini)
{
	global $tpl, $hp_name, $hp_php, $hp_cgi, $hp_sub, $hp_als, $hp_mail, $hp_ftp,
		$hp_sql_db, $hp_sql_user, $hp_traff, $hp_disk, $hpid, $hp_backup, $hp_dns,
		$hp_allowsoftware, $hp_ext_mail;
	//	global $tos;

	$description = clean_input($_POST['hp_description']);
	$price = clean_input($_POST['hp_price']);
	$setup_fee = clean_input($_POST['hp_setupfee']);
	$currency = clean_input($_POST['hp_currency']);
	$payment = clean_input($_POST['hp_payment']);
	$status = clean_input($_POST['status']);
	$tos = clean_input($_POST['hp_tos']);


	$hp_props = "$hp_php;$hp_cgi;$hp_sub;$hp_als;$hp_mail;$hp_ftp;$hp_sql_db;" .
				"$hp_sql_user;$hp_traff;$hp_disk;$hp_backup;$hp_dns;$hp_allowsoftware";
	$hp_props .= ";" . $phpini->getClPermVal('phpiniSystem') . ";" . $phpini->getClPermVal('phpiniAllowUrlFopen');
	$hp_props .= ";" . $phpini->getClPermVal('phpiniDisplayErrors') . ";" . $phpini->getClPermVal('phpiniDisableFunctions');
	$hp_props .= ";" . $phpini->getDataVal('phpiniPostMaxSize') . ";" . $phpini->getDataVal('phpiniUploadMaxFileSize') . ";" . $phpini->getDataVal('phpiniMaxExecutionTime');
	$hp_props .= ";" . $phpini->getDataVal('phpiniMaxInputTime') . ";" . $phpini->getDataVal('phpiniMemoryLimit') . ";" .$hp_ext_mail;


	$admin_id = $_SESSION['user_id'];

	if (reseller_limits_check($admin_id, $hpid, $hp_props)) {
			$query = "
				UPDATE
					`hosting_plans`
				SET
					`name` = ?,
					`description` = ?,
					`props` = ?,
					`price` = ?,
					`setup_fee` = ?,
					`value` = ?,
					`payment` = ?,
					`status` = ?,
					`tos` = ?
				WHERE
					`id` = ?
			";

			exec_query(
				$query,
				array(
					 $hp_name, $description, $hp_props, $price, $setup_fee,
					 $currency, $payment, $status, $tos, $hpid
				)
			);

			$_SESSION['hp_updated'] = '_yes_';
			redirectTo('hosting_plan.php');
			exit; // Useless but avoid stupid IDE warning about missing return statement
	} else {
		set_page_message(tr('Hosting plan values exceed reseller maximum values.'), 'error');
		restore_form($tpl);
		return false;
	}
}
