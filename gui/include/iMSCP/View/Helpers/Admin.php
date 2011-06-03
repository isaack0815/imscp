<?php
/**
 * i-MSCP a internet Multi Server Control Panel
 *
 * @copyright   2001-2006 by moleSoftware GmbH
 * @copyright   2006-2010 by ispCP | http://isp-control.net
 * @copyright   2010-2011 by i-MSCP | http://i-mscp.net
 * @version     SVN: $Id$
 * @link        http://i-mscp.net
 * @author      ispCP Team
 * @author      i-MSCP Team
 *
 * @license
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
 * Portions created by the ispCP Team are Copyright (C) 2006-2010 by
 * isp Control Panel. All Rights Reserved.
 * Portions created by the i-MSCP Team are Copyright (C) 2010 by
 * i-MSCP a internet Multi Server Control Panel. All Rights Reserved.
 */

/************************************************************************************
 * This file contains view helpers functions that are responsible to generate
 * template parts for admin interface such as the main and left menus.
 */

/**
 * Helper function to generate the main menu from a partial template.
 *
 * @param  iMSCP_pTemplate $tpl iMSCP_pTemplate instance
 * @param  string $menu_file Partial template file path
 * @return void
 */
function gen_admin_mainmenu($tpl, $menu_file)
{
     /** @var $cfg iMSCP_Config_Handler_File */
    $cfg = iMSCP_Registry::get('config');

    /** @var $db iMSCP_Database */
    $db = iMSCP_Registry::get('db');

    $tpl->define_dynamic('menu', $menu_file);
    $tpl->define_dynamic('isactive_support', 'menu');
    $tpl->define_dynamic('custom_buttons', 'menu');
    $tpl->assign(array(
                      'TR_MENU_GENERAL_INFORMATION' => tr('General information'),
                      'TR_MENU_HOSTING_PLANS' => tr('Manage hosting plans'),
                      'TR_MENU_SYSTEM_TOOLS' => tr('System tools'),
                      'TR_MENU_MANAGE_USERS' => tr('Manage users'),
                      'TR_MENU_STATISTICS' => tr('Statistics'),
                      'SUPPORT_SYSTEM_PATH' => $cfg->IMSCP_SUPPORT_SYSTEM_PATH,
                      'SUPPORT_SYSTEM_TARGET' => $cfg->IMSCP_SUPPORT_SYSTEM_TARGET,
                      'TR_MENU_SUPPORT_SYSTEM' => tr('Support system'),
                      'TR_MENU_SETTINGS' => tr('Settings'),
                      'TR_MENU_GENERAL_INFORMATION' => tr('General information'),
                      'TR_MENU_HOSTING_PLANS' => tr('Manage hosting plans'),
                      'TR_MENU_SYSTEM_TOOLS' => tr('System tools'),
                      'TR_MENU_MANAGE_USERS' => tr('Manage users'),
                      'TR_MENU_STATISTICS' => tr('Statistics'),
                      'SUPPORT_SYSTEM_PATH' => $cfg->IMSCP_SUPPORT_SYSTEM_PATH,
                      'SUPPORT_SYSTEM_TARGET' => $cfg->IMSCP_SUPPORT_SYSTEM_TARGET,
                      'TR_MENU_SUPPORT_SYSTEM' => tr('Support system'),
                      'TR_MENU_SETTINGS' => tr('Settings'),
                      'TR_MENU_CHANGE_PASSWORD' => tr('Change password'),
                      'TR_MENU_CHANGE_PERSONAL_DATA' => tr('Change personal data'),
                      'TR_MENU_ADD_ADMIN' => tr('Add admin'),
                      'TR_MENU_ADD_RESELLER' => tr('Add reseller'),
                      'TR_MENU_RESELLER_ASIGNMENT' => tr('Reseller assignment'),
                      'TR_MENU_USER_ASIGNMENT' => tr('User assignment'),
                      'TR_MENU_EMAIL_SETUP' => tr('Email setup'),
                      'TR_MENU_CIRCULAR' => tr('Email marketing'),
                      'TR_MENU_ADD_HOSTING' => tr('Add hosting plan'),
                      'TR_MENU_RESELLER_STATISTICS' => tr('Reseller statistics'),
                      'TR_MENU_SERVER_STATISTICS' => tr('Server statistics'),
                      'TR_MENU_ADMIN_LOG' => tr('Admin log'),
                      'TR_MENU_MANAGE_IPS' => tr('Manage IPs'),
                      'TR_MENU_SYSTEM_INFO' => tr('System info'),
                      'TR_MENU_I18N' => tr('Internationalisation'),
                      'TR_MENU_LANGUAGE' => tr('Language'),
                      'TR_MENU_LAYOUT_TEMPLATES' => tr('Layout'),
                      'TR_MENU_LOGOUT' => tr('Logout'),
                      'TR_MENU_QUESTIONS_AND_COMMENTS' => tr('Support system'),
                      'TR_MENU_SERVER_TRAFFIC_SETTINGS' => tr('Server traffic settings'),
                      'TR_MENU_SERVER_STATUS' => tr('Server status'),
                      'TR_MENU_IMSCP_UPDATE' => tr('i-MSCP updates'),
                      'TR_MENU_IMSCP_DATABASE_UPDATE' => tr('i-MSCP database updates'),
                      'TR_MENU_IMSCP_DEBUGGER' => tr('i-MSCP debugger'),
                      'TR_CUSTOM_MENUS' => tr('Custom menus'),
                      'TR_MENU_OVERVIEW' => tr('Overview'),
                      'TR_MENU_MANAGE_SESSIONS' => tr('User sessions'),
                      'TR_MENU_LOSTPW_EMAIL' => tr('Lostpw email setup'),
                      'TR_MAINTENANCEMODE' => tr('Maintenance mode'),
                      'TR_GENERAL_SETTINGS' => tr('General settings'),
                      'TR_SERVERPORTS' => tr('Server ports')));

    $query = "SELECT * FROM `custom_menus` WHERE `menu_level` = 'admin';";
    $rs = exec_query($db, $query);

    if ($rs->recordCount() == 0) {
        $tpl->assign('CUSTOM_BUTTONS', '');
    } else {
        global $i;
        $i = 100;

        while (!$rs->EOF) {
            $menu_name = $rs->fields['menu_name'];
            $menu_link = get_menu_vars($rs->fields['menu_link']);
            $menu_target = $rs->fields['menu_target'];

            if ($menu_target !== '') {
                $menu_target = 'target="' . tohtml($menu_target) . '"';
            }

            $tpl->assign(array(
                              'BUTTON_LINK' => tohtml($menu_link),
                              'BUTTON_NAME' => tohtml($menu_name),
                              'BUTTON_TARGET' => $menu_target,
                              'BUTTON_ID' => $i));

            $tpl->parse('CUSTOM_BUTTONS', '.custom_buttons');
            $rs->moveNext();
            $i++;
        }
    }

    if (!$cfg->IMSCP_SUPPORT_SYSTEM) {
        $tpl->assign('ISACTIVE_SUPPORT', '');
    }

    if (strtolower($cfg->HOSTING_PLANS_LEVEL) != 'admin') {
        $tpl->assign('HOSTING_PLANS', '');
    }

    $tpl->parse('MAIN_MENU', 'menu');
}

/**
 * Helper function to render left menu from a partial template.
 *
 * @param  iMSCP_pTemplate $tpl iMSCP_pTemplate instance
 * @param  string $menu_file Partial template file path
 */
function gen_admin_menu($tpl, $menu_file)
{
     /** @var $cfg iMSCP_Config_Handler_File */
    $cfg = iMSCP_Registry::get('config');

    /** @var $db iMSCP_Database */
    $db = iMSCP_Registry::get('db');

    $tpl->define_dynamic('menu', $menu_file);
    $tpl->define_dynamic('custom_buttons', 'menu');
    $tpl->assign(array(
                      'TR_MENU_GENERAL_INFORMATION' => tr('General information'),
                      'TR_MENU_CHANGE_PASSWORD' => tr('Change password'),
                      'TR_MENU_CHANGE_PERSONAL_DATA' => tr('Change personal data'),
                      'TR_MENU_MANAGE_USERS' => tr('Manage users'),
                      'TR_MENU_ADD_ADMIN' => tr('Add admin'),
                      'TR_MENU_ADD_RESELLER' => tr('Add reseller'),
                      'TR_MENU_RESELLER_ASIGNMENT' => tr('Reseller assignment'),
                      'TR_MENU_USER_ASIGNMENT' => tr('User assignment'),
                      'TR_MENU_EMAIL_SETUP' => tr('Email setup'),
                      'TR_MENU_CIRCULAR' => tr('Email marketing'),
                      'TR_MENU_HOSTING_PLANS' => tr('Manage hosting plans'),
                      'TR_MENU_ADD_HOSTING' => tr('Add hosting plan'),
                      'TR_MENU_ROOTKIT_LOG' => tr('Rootkit Log'),
                      'TR_MENU_RESELLER_STATISTICS' => tr('Reseller statistics'),
                      'TR_MENU_SERVER_STATISTICS' => tr('Server statistics'),
                      'TR_MENU_ADMIN_LOG' => tr('Admin log'),
                      'TR_MENU_MANAGE_IPS' => tr('Manage IPs'),
                      'TR_MENU_SUPPORT_SYSTEM' => tr('Support system'),
                      'TR_MENU_SYSTEM_INFO' => tr('System info'),
                      'TR_MENU_I18N' => tr('Internationalisation'),
                      'TR_MENU_LANGUAGE' => tr('Language'),
                      'TR_MENU_LAYOUT_TEMPLATES' => tr('Layout'),
                      'TR_MENU_LOGOUT' => tr('Logout'),
                      'TR_MENU_QUESTIONS_AND_COMMENTS' => tr('Support system'),
                      'TR_MENU_STATISTICS' => tr('Statistics'),
                      'TR_MENU_SYSTEM_TOOLS' => tr('System tools'),
                      'TR_MENU_SERVER_TRAFFIC_SETTINGS' => tr('Server traffic settings'),
                      'TR_MENU_SERVER_STATUS' => tr('Services status'),
                      'TR_MENU_IMSCP_UPDATE' => tr('i-MSCP updates'),
                      'TR_MENU_IMSCP_DEBUGGER' => tr('i-MSCP debugger'),
                      'TR_CUSTOM_MENUS' => tr('Custom menus'),
                      'TR_MENU_OVERVIEW' => tr('Overview'),
                      'TR_MENU_MANAGE_SESSIONS' => tr('User sessions'),
                      'SUPPORT_SYSTEM_PATH' => $cfg->IMSCP_SUPPORT_SYSTEM_PATH,
                      'SUPPORT_SYSTEM_TARGET' => $cfg->IMSCP_SUPPORT_SYSTEM_TARGET,
                      'TR_MENU_LOSTPW_EMAIL' => tr('Lostpw email setup'),
                      'TR_MAINTENANCEMODE' => tr('Maintenance mode'),
                      'TR_MENU_SETTINGS' => tr('Settings'),
                      'TR_GENERAL_SETTINGS' => tr('General settings'),
                      'TR_SERVERPORTS' => tr('Services ports'),
                      'TR_MENU_IP_USAGE' => tr('IP usage'),
                      'TR_MENU_MANAGE_SOFTWARE' => tr('Application management'),
                      'TR_MENU_SOFTWARE_OPTIONS' => tr('Application options'),
                      'VERSION' => $cfg->Version,
                      'BUILDDATE' => $cfg->BuildDate,
                      'CODENAME' => $cfg->CodeName));

    $query = "SELECT * FROM `custom_menus` WHERE `menu_level` = 'admin1';";
    $rs = exec_query($db, $query);

    if ($rs->recordCount() == 0) {
        $tpl->assign('CUSTOM_BUTTONS', '');
    } else {
        global $i;
        $i = 100;

        while (!$rs->EOF) {
            $menu_name = $rs->fields['menu_name'];
            $menu_link = get_menu_vars($rs->fields['menu_link']);
            $menu_target = $rs->fields['menu_target'];

            if ($menu_target !== '') {
                $menu_target = 'target="' . tohtml($menu_target) . '"';
            }

            $tpl->assign(array(
                              'BUTTON_LINK' => tohtml($menu_link),
                              'BUTTON_NAME' => tohtml($menu_name),
                              'BUTTON_TARGET' => $menu_target,
                              'BUTTON_ID' => $i));

            $tpl->parse('CUSTOM_BUTTONS', '.custom_buttons');
            $rs->moveNext();
            $i++;
        }
    }

    if (!$cfg->IMSCP_SUPPORT_SYSTEM) {
        $tpl->assign('SUPPORT_SYSTEM', '');
    }

    if (strtolower($cfg->HOSTING_PLANS_LEVEL) != 'admin') {
        $tpl->assign('HOSTING_PLANS', '');
    }

    $tpl->parse('MENU', 'menu');
}

/**
 * Helper function to generate admin general information template part.
 *
 * @param  iMSCP_pTemplate $tpl iMSCP_pTemplate instance
 * @param  iMSCP_Database $db iMSCP_Database instance
 * @return void
 */
function getAdminGeneralInfo($tpl, $db)
{
    /** @var $cfg  iMSCP_Config_Handler_File */
    $cfg = iMSCP_Registry::get('config');

    $tpl->assign(array(
                      'TR_GENERAL_INFORMATION' => tr('General information'),
                      'TR_ACCOUNT_NAME' => tr('Account name'),
                      'TR_ADMIN_USERS' => tr('Admin users'),
                      'TR_RESELLER_USERS' => tr('Reseller users'),
                      'TR_NORMAL_USERS' => tr('Normal users'),
                      'TR_DOMAINS' => tr('Domains'),
                      'TR_SUBDOMAINS' => tr('Subdomains'),
                      'TR_DOMAINS_ALIASES' => tr('Domain aliases'),
                      'TR_MAIL_ACCOUNTS' => tr('Mail accounts'),
                      'TR_FTP_ACCOUNTS' => tr('FTP accounts'),
                      'TR_SQL_DATABASES' => tr('SQL databases'),
                      'TR_SQL_USERS' => tr('SQL users'),
                      'TR_SYSTEM_MESSAGES' => tr('System messages'),
                      'TR_NO_NEW_MESSAGES' => tr('No new messages'),
                      'TR_SERVER_TRAFFIC' => tr('Server traffic')));

    // If COUNT_DEFAULT_EMAIL_ADDRESSES = false, admin total emails show
    // [total - default_emails]/[total_emails]
    $retrieve_total_emails = records_count(
        'mail_users', 'mail_type NOT RLIKE \'_catchall\'', ''
    );

    if ($cfg->COUNT_DEFAULT_EMAIL_ADDRESSES) {
        $show_total_emails = $retrieve_total_emails;
    } else {
        $retrieve_total_default_emails = records_count('mail_users', 'mail_acc',
                                                       'abuse');

        $retrieve_total_default_emails += records_count('mail_users', 'mail_acc',
                                                        'webmaster');

        $retrieve_total_default_emails += records_count('mail_users', 'mail_acc',
                                                        'postmaster');

        $show_total_emails = ($retrieve_total_emails - $retrieve_total_default_emails)
                             . '/' . $retrieve_total_emails;
    }

    $tpl->assign(array(
                      'ACCOUNT_NAME' => $_SESSION['user_logged'],
                      'ADMIN_USERS' => records_count('admin', 'admin_type', 'admin'),
                      'RESELLER_USERS' => records_count('admin', 'admin_type', 'reseller'),
                      'NORMAL_USERS' => records_count('admin', 'admin_type', 'user'),
                      'DOMAINS' => records_count('domain', '', ''),
                      'SUBDOMAINS' => records_count('subdomain', '', '') +
                                      records_count('subdomain_alias',
                                                    'subdomain_alias_id', '', ''),
                      'DOMAINS_ALIASES' => records_count('domain_aliasses', '', ''),
                      'MAIL_ACCOUNTS' => $show_total_emails,
                      'FTP_ACCOUNTS' => records_count('ftp_users', '', ''),
                      'SQL_DATABASES' => records_count('sql_database', '', ''),
                      'SQL_USERS' => get_sql_user_count($db)));
}

/**
 * Helper function to generate admin list template part.
 *
 * @param  iMSCP_pTemplate $tpl iMSCP_pTemplate instance
 * @param  iMSCP_Database $db iMSCP_Database instance
 * @return void
 */
function gen_admin_list($tpl, $db)
{
    /** @var $cfg  iMSCP_Config_Handler_File */
    $cfg = iMSCP_Registry::get('config');

    $query = "
		SELECT
			t1.`admin_id`, t1.`admin_name`, t1.`domain_created`,
			IFNULL(t2.`admin_name`, '') AS `created_by`
		FROM
			`admin` AS `t1`
		LEFT JOIN
			`admin` AS `t2` ON `t1`.`created_by` = t2.`admin_id`
		WHERE
			`t1`.`admin_type` = 'admin'
		ORDER BY
			`t1`.`admin_name` ASC
		;
	";
    $rs = exec_query($db, $query);

    if ($rs->recordCount() == 0) {
        $tpl->assign(array(
                          'ADMIN_MESSAGE' => tr('Administrators list is empty!'),
                          'ADMIN_LIST' => ''));

        $tpl->parse('ADMIN_MESSAGE', 'admin_message');
    } else {
        $tpl->assign(array(
                          'TR_ADMIN_USERNAME' => tr('Username'),
                          'TR_ADMIN_CREATED_ON' => tr('Creation date'),
                          'TR_ADMIN_CREATED_BY' => tr('Created by'),
                          'TR_ADMIN_OPTIONS' => tr('Options')));

        $i = 0;
        while (!$rs->EOF) {
            $tpl->assign('ADMIN_CLASS', ($i % 2 == 0) ? 'content' : 'content2');
            $admin_created = $rs->fields['domain_created'];

            if ($admin_created == 0) {
                $admin_created = tr('N/A');
            } else {
                $date_formt = $cfg->DATE_FORMAT;
                $admin_created = date($date_formt, $admin_created);
            }

            if ($rs->fields['created_by'] == '' ||
                $rs->fields['admin_id'] == $_SESSION['user_id']
            ) {

                $tpl->assign('ADMIN_DELETE_LINK', '');
                $tpl->parse('ADMIN_DELETE_SHOW', 'admin_delete_show');
            } else {
                $tpl->assign(array(
                                  'ADMIN_DELETE_SHOW' => '',
                                  'TR_DELETE' => tr('Delete'),
                                  'URL_DELETE_ADMIN' => 'user_delete.php?delete_id='
                                                        . $rs->fields['admin_id'] .
                                                        '&amp;delete_username=' .
                                                        $rs->fields['admin_name'],
                                  'ADMIN_USERNAME' => tohtml($rs->fields['admin_name'])));

                $tpl->parse('ADMIN_DELETE_LINK', 'admin_delete_link');
            }

            $tpl->assign(array(
                              'ADMIN_USERNAME' => tohtml($rs->fields['admin_name']),
                              'ADMIN_CREATED_ON' => tohtml($admin_created),
                              'ADMIN_CREATED_BY' => ($rs->fields['created_by'] != null)
                                  ? tohtml($rs->fields['created_by']) : tr("System"),
                              'URL_EDIT_ADMIN' => 'admin_edit.php?edit_id=' .
                                                  $rs->fields['admin_id']));

            $tpl->parse('ADMIN_ITEM', '.admin_item');
            $rs->moveNext();
            $i++;
        }

        $tpl->parse('ADMIN_LIST', 'admin_list');
        $tpl->assign('ADMIN_MESSAGE', '');
    }
}

/**
 * Helper function to generate reseller list template part.
 *
 * @param  iMSCP_pTemplate $tpl iMSCP_pTemplate instance
 * @param  iMSCP_Database $db iMSCP_Database instance
 * @return void
 */
function gen_reseller_list($tpl, $db)
{
     /** @var $cfg iMSCP_Config_Handler_File */
    $cfg = iMSCP_Registry::get('config');

    $query = "
		SELECT
				`t1`.`admin_id`, `t1`.`admin_name`, `t1`.`domain_created`,
				IFNULL(t2.`admin_name`, '') AS created_by
		FROM
				`admin` AS `t1`
		LEFT JOIN
				`admin` AS `t2` ON `t1`.`created_by` = t2.`admin_id`
		WHERE
				`t1`.`admin_type` = 'reseller'
		ORDER BY
				`t1`.`admin_name` ASC
		;
	";
    $rs = exec_query($db, $query);

    if ($rs->recordCount() == 0) {
        $tpl->assign(array(
                          'RSL_MESSAGE' => tr('Resellers list is empty!'),
                          'RSL_LIST' => ''));

        $tpl->parse('RSL_MESSAGE', 'rsl_message');
    } else {
        $tpl->assign(array(
                          'TR_RSL_USERNAME' => tr('Username'),
                          'TR_RSL_CREATED_BY' => tr('Created by'),
                          'TR_RSL_OPTIONS' => tr('Options')));

        $i = 0;
        while (!$rs->EOF) {
            // @todo Not longer needed with the new theme
            $tpl->assign('RSL_CLASS', ($i % 2 == 0) ? 'content' : 'content2');

            if ($rs->fields['created_by'] == '') {
                $tpl->assign(array(
                                  'TR_DELETE' => tr('Delete'),
                                  'RSL_DELETE_LINK' => ''));

                $tpl->parse('RSL_DELETE_SHOW', 'rsl_delete_show');
            } else {
                $tpl->assign(array(
                                  'RSL_DELETE_SHOW' => '',
                                  'TR_DELETE' => tr('Delete'),
                                  'URL_DELETE_RSL' => 'user_delete.php?delete_id=' .
                                                      $rs->fields['admin_id'] .
                                                      '&amp;delete_username=' .
                                                      $rs->fields['admin_name'],
                                  'TR_CHANGE_USER_INTERFACE' => tr('Switch to user interface'),
                                  'GO_TO_USER_INTERFACE' => tr('Switch'),
                                  'URL_CHANGE_INTERFACE' => 'change_user_interface.php?to_id=' .
                                                            $rs->fields['admin_id']));

                $tpl->parse('RSL_DELETE_LINK', 'rsl_delete_link');
            }

            $reseller_created = $rs->fields['domain_created'];

            if ($reseller_created == 0) {
                $reseller_created = tr('N/A');
            } else {
                $date_formt = $cfg->DATE_FORMAT;
                $reseller_created = date($date_formt, $reseller_created);
            }

            $tpl->assign(array(
                              'RSL_USERNAME' => tohtml($rs->fields['admin_name']),
                              'RESELLER_CREATED_ON' => tohtml($reseller_created),
                              'RSL_CREATED_BY' => tohtml($rs->fields['created_by']),
                              'URL_EDIT_RSL' => 'reseller_edit.php?edit_id=' .
                                                $rs->fields['admin_id']));

            $tpl->parse('RSL_ITEM', '.rsl_item');
            $rs->moveNext();
            $i++;
        }

        $tpl->parse('RSL_LIST', 'rsl_list');
        $tpl->assign('RSL_MESSAGE', '');
    }
}

/**
 * Helper function to generate an user list.
 *
 * @param  iMSCP_pTemplate $tpl iMSCP_pTemplate instance
 * @param  iMSCP_Database $db iMSCP_Database instance
 * @return void
 */
function gen_user_list($tpl, $db)
{
     /** @var $cfg iMSCP_Config_Handler_File */
    $cfg = iMSCP_Registry::get('config');

    $start_index = 0;
    $rows_per_page = $cfg->DOMAIN_ROWS_PER_PAGE;

    if (isset($_GET['psi'])) {
        $start_index = $_GET['psi'];
    }

    // Search request generated ?!
    if (isset($_POST['uaction']) && !empty($_POST['uaction'])) {
        $_SESSION['search_for'] = trim(clean_input($_POST['search_for']));
        $_SESSION['search_common'] = $_POST['search_common'];
        $_SESSION['search_status'] = $_POST['search_status'];
        $start_index = 0;
    } elseif (isset($_SESSION['search_for']) && !isset($_GET['psi'])) {
        // He have not got scroll through patient records.
        unset($_SESSION['search_for']);
        unset($_SESSION['search_common']);
        unset($_SESSION['search_status']);
    }

    $search_query = '';
    $count_query = '';

    if (isset($_SESSION['search_for'])) {
        gen_admin_domain_query($search_query, $count_query, $start_index,
                               $rows_per_page, $_SESSION['search_for'],
                               $_SESSION['search_common'], $_SESSION['search_status']);

        gen_admin_domain_search_options($tpl, $_SESSION['search_for'],
                                        $_SESSION['search_common'],
                                        $_SESSION['search_status']);

        $rs = exec_query($db, $count_query);
    } else {
        gen_admin_domain_query($search_query, $count_query, $start_index,
                               $rows_per_page, 'n/a', 'n/a', 'n/a');

        gen_admin_domain_search_options($tpl, 'n/a', 'n/a', 'n/a');
        $rs = exec_query($db, $count_query);
    }

    $records_count = $rs->fields['cnt'];
    $rs = execute_query($db, $search_query);
    $i = 0;

    if ($rs->recordCount() == 0) {
        if (isset($_SESSION['search_for'])) {
            $tpl->assign(array(
                              'USR_MESSAGE' => tr('Not found user records matching the search criteria!'),
                              'USR_LIST' => '',
                              'SCROLL_PREV' => '',
                              'SCROLL_NEXT' => '',
                              'TR_VIEW_DETAILS' => tr('view aliases'),
                              'SHOW_DETAILS' => 'show'));

            unset($_SESSION['search_for']);
            unset($_SESSION['search_common']);
            unset($_SESSION['search_status']);
        } else {
            $tpl->assign(array(
                              'USR_MESSAGE' => tr('Users list is empty!'),
                              'USR_LIST' => '',
                              'SCROLL_PREV' => '',
                              'SCROLL_NEXT' => '',
                              'TR_VIEW_DETAILS' => tr('view aliases'),
                              'SHOW_DETAILS' => 'show'));
        }

        $tpl->parse('USR_MESSAGE', 'usr_message');
    } else {
        $prev_si = $start_index - $rows_per_page;

        if ($start_index == 0) {
            $tpl->assign('SCROLL_PREV', '');
        } else {
            $tpl->assign(array(
                              'SCROLL_PREV_GRAY' => '',
                              'PREV_PSI' => $prev_si));
        }

        $next_si = $start_index + $rows_per_page;

        if ($next_si + 1 > $records_count) {
            $tpl->assign('SCROLL_NEXT', '');
        } else {
            $tpl->assign(array(
                              'SCROLL_NEXT_GRAY' => '',
                              'NEXT_PSI' => $next_si));
        }

        $tpl->assign(array(
                          'TR_USR_USERNAME' => tr('Username'),
                          'TR_USR_CREATED_BY' => tr('Created by'),
                          'TR_USR_OPTIONS' => tr('Options'),
                          'TR_USER_STATUS' => tr('Status'),
                          'TR_DETAILS' => tr('Details')));

        while (!$rs->EOF) {
            // @todo Not longer needed with the new theme
            $tpl->assign('USR_CLASS', ($i % 2 == 0) ? 'content' : 'content2');

            // user status icon
            $domain_created_id = $rs->fields['domain_created_id'];

            $query = "
				SELECT
						`admin_name`
				FROM
						`admin`
				WHERE
						`admin_id` = ?
				ORDER BY
						`admin_name` ASC
				;
			";
            $rs2 = exec_query($db, $query, $domain_created_id);

            if (!isset($rs2->fields['admin_name'])) {
                $created_by_name = tr('N/A');
            } else {
                $created_by_name = $rs2->fields['admin_name'];
            }

            // Get disk usage by user
            $tpl->assign(array(
                              'USR_DELETE_SHOW' => '',
                              'DOMAIN_ID' => $rs->fields['domain_id'],
                              'TR_DELETE' => tr('Delete'),
                              'URL_DELETE_USR' => 'user_delete.php?domain_id=' .
                                                  $rs->fields['domain_id'],
                              'TR_CHANGE_USER_INTERFACE' => tr('Switch to user interface'),
                              'GO_TO_USER_INTERFACE' => tr('Switch'),
                              'URL_CHANGE_INTERFACE' => 'change_user_interface.php?to_id=' .
                                                        $rs->fields['domain_admin_id'],
                              'USR_USERNAME' => tohtml($rs->fields['domain_name']),
                              'TR_EDIT_DOMAIN' => tr('Edit domain'),
                              'TR_EDIT_USR' => tr('Edit user')));

            $tpl->parse('USR_DELETE_LINK', 'usr_delete_link');

            if ($rs->fields['domain_status'] == $cfg->ITEM_OK_STATUS) {
                $status = 'ok';
                $status_txt = tr('Ok');
                $status_url = 'domain_status_change.php?domain_id=' .
                              $rs->fields['domain_id'];
            } elseif ($rs->fields['domain_status'] == $cfg->ITEM_DISABLED_STATUS) {
                $status = 'disabled';
                $status_txt = tr('Disabled');
                $status_url = 'domain_status_change.php?domain_id=' .
                              $rs->fields['domain_id'];
            } elseif ($rs->fields['domain_status'] == $cfg->ITEM_ADD_STATUS
                      || $rs->fields['domain_status'] == $cfg->ITEM_RESTORE_STATUS
                      || $rs->fields['domain_status'] == $cfg->ITEM_CHANGE_STATUS
                      || $rs->fields['domain_status'] == $cfg->ITEM_TOENABLE_STATUS
                      || $rs->fields['domain_status'] == $cfg->ITEM_TODISABLED_STATUS
                      || $rs->fields['domain_status'] == $cfg->ITEM_DELETE_STATUS
            ) {
                $status = 'reload';
                $status_txt = tr('Reload');
                $status_url = '#';
            } else {
                $status = 'error';
                $status_txt = tr('Error');
                $status_url = 'domain_details.php?domain_id=' . $rs->fields['domain_id'];
            }

            $tpl->assign(array(
                              'STATUS_ICON' => $status . '.png', // Only used in omega_original
                              'STATUS' => $status,
                              'TR_STATUS' => $status_txt,
                              'URL_CHANGE_STATUS' => $status_url,));

            // end of user status icon
            $admin_name = decode_idna($rs->fields['domain_name']);
            $domain_created = $rs->fields['domain_created'];

            if ($domain_created == 0) {
                $domain_created = tr('N/A');
            } else {
                $date_formt = $cfg->DATE_FORMAT;
                $domain_created = date($date_formt, $domain_created);
            }

            $domain_expires = $rs->fields['domain_expires'];

            if ($domain_expires == 0) {
                $domain_expires = tr('Not Set');
            } else {
                $date_formt = $cfg->DATE_FORMAT;
                $domain_expires = date($date_formt, $domain_expires);
            }

            $tpl->assign(array(
                              'USR_USERNAME' => tohtml($admin_name),
                              'USER_CREATED_ON' => tohtml($domain_created),
                              'USER_EXPIRES_ON' => $domain_expires,
                              'USR_CREATED_BY' => tohtml($created_by_name),
                              'USR_OPTIONS' => '',
                              'URL_EDIT_USR' => 'admin_edit.php?edit_id=' .
                                                $rs->fields['domain_admin_id'],
                              'TR_MESSAGE_CHANGE_STATUS' => tr('Are you sure you want to change the status of domain account?', true),
                              'TR_MESSAGE_DELETE' => tr('Are you sure you want to delete %s?', true, '%s')));

            gen_domain_details($tpl, $db, $rs->fields['domain_id']);
            $tpl->parse('USR_ITEM', '.usr_item');
            $rs->moveNext();
            $i++;
        }

        $tpl->parse('USR_LIST', 'usr_list');
        $tpl->assign('USR_MESSAGE', '');
    }
}

/**
 * Helper function to generate manage users template part.
 *
 * @param  iMSCP_pTemplate $tpl iMSCP_pTemplate instance
 * @param  iMSCP_Database $db iMSCP_Database instance
 * @return void
 */
function get_admin_manage_users($tpl, $db)
{
    $tpl->assign(array(
                      'TR_MANAGE_USERS' => tr('Manage users'),
                      'TR_ADMINISTRATORS' => tr('Administrators'),
                      'TR_RESELLERS' => tr('Resellers'),
                      'TR_USERS' => tr('Users'),
                      'TR_SEARCH' => tr('Search'),
                      'TR_CREATED_ON' => tr('Creation date'),
                      'TR_EXPIRES_ON' => tr('Expire date'),
                      'TR_MESSAGE_DELETE' => tr('Are you sure you want to delete %s?', true, '%s'),
                      'TR_EDIT' => tr('Edit')));

    gen_admin_list($tpl, $db);
    gen_reseller_list($tpl, $db);
    gen_user_list($tpl, $db);
}

/**
 * Helper function to generate HTML list of months and years
 *
 * @param  iMSCP_pTemplate $tpl iMSCP_pTemplate instance
 * @param  $user_month
 * @param  $user_year
 * @return void
 */
function gen_select_lists($tpl, $user_month, $user_year)
{
    global $crnt_month, $crnt_year;

     /** @var $cfg iMSCP_Config_Handler_File */
    $cfg = iMSCP_Registry::get('config');

    if (!$user_month == '' || !$user_year == '') {
        $crnt_month = $user_month;
        $crnt_year = $user_year;
    } else {
        $crnt_month = date('m');
        $crnt_year = date('Y');
    }

    for ($i = 1; $i <= 12; $i++) {
        $selected = ($i == $crnt_month) ? $cfg->HTML_SELECTED : '';
        $tpl->assign(array('OPTION_SELECTED' => $selected, 'MONTH_VALUE' => $i));
        $tpl->parse('MONTH_LIST', '.month_list');
    }

    for ($i = $crnt_year - 1; $i <= $crnt_year + 1; $i++) {
        $selected = ($i == $crnt_year) ? $cfg->HTML_SELECTED : '';
        $tpl->assign(array('OPTION_SELECTED' => $selected, 'YEAR_VALUE' => $i));
        $tpl->parse('YEAR_LIST', '.year_list');
    }
}

/**
 * Helper function to generate logged from template part.
 *
 * @param  iMSCP_pTemplate $tpl iMSCP_pTemplate instance
 * @return void
 */
function gen_logged_from($tpl)
{
    if (isset($_SESSION['logged_from']) && isset($_SESSION['logged_from_id'])) {
        $tpl->assign(array(
                          'YOU_ARE_LOGGED_AS' => tr(
                              '%1$s you are now logged as %2$s',
                              $_SESSION['logged_from'],
                              decode_idna($_SESSION['user_logged'])
                          ),
                          'TR_GO_BACK' => tr('Go back')));

        $tpl->parse('LOGGED_FROM', '.logged_from');
    } else {
        $tpl->assign('LOGGED_FROM', '');
    }
}

/**
 * Helper function to generate domain search form template part.
 *
 * @param  iMSCP_pTemplate $tpl iMSCP_pTemplate instance
 * @param  string $search_for Object to search for
 * @param  $search_common Commone object to search for
 * @param  $search_status Object status to search for
 * @return void
 */
function gen_admin_domain_search_options($tpl, $search_for, $search_common, $search_status)
{
     /** @var $cfg iMSCP_Config_Handler_File */
    $cfg = iMSCP_Registry::get('config');

     $domain_selected = $customerid_selected = $lastname_selected =
     $company_selected = $city_selected = $state_selected = $country_selected =
     $all_selected = $ok_selected = $suspended_selected = '';

    if ($search_for == 'n/a' && $search_common == 'n/a' && $search_status == 'n/a') {
        // we have no search and let's generate search fields empty
        $domain_selected = $cfg->HTML_SELECTED;
        $all_selected = $cfg->HTML_SELECTED;
    }

    if ($search_common == 'domain_name') {
        $domain_selected = $cfg->HTML_SELECTED;
    } elseif ($search_common == 'customer_id') {
        $customerid_selected = $cfg->HTML_SELECTED;
    } elseif ($search_common == 'lname') {
        $lastname_selected = $cfg->HTML_SELECTED;
    } elseif ($search_common === 'firm') {
        $company_selected = $cfg->HTML_SELECTED;
    } elseif ($search_common == 'city') {
        $city_selected = $cfg->HTML_SELECTED;
    } elseif ($search_common == 'state') {
        $state_selected = $cfg->HTML_SELECTED;
    } elseif ($search_common == 'country') {
        $country_selected = $cfg->HTML_SELECTED;
    }

    if ($search_status == 'all') {
        $all_selected = $cfg->HTML_SELECTED;
    } elseif ($search_status == 'ok') {
        $ok_selected = $cfg->HTML_SELECTED;
    } elseif ($search_status == 'disabled') {
        $suspended_selected = $cfg->HTML_SELECTED;
    }

    if ($search_for == 'n/a' || $search_for == '') {
        $tpl->assign(array('SEARCH_FOR' => ''));
    } else {
        $tpl->assign(array('SEARCH_FOR' => $search_for));
    }

    $tpl->assign(array(
                      'M_DOMAIN_NAME' => tr('Domain name'),
                      'M_CUSTOMER_ID' => tr('Customer ID'),
                      'M_LAST_NAME' => tr('Last name'),
                      'M_COMPANY' => tr('Company'),
                      'M_CITY' => tr('City'),
                      'M_STATE' => tr('State/Province'),
                      'M_COUNTRY' => tr('Country'),
                      'M_ALL' => tr('All'),
                      'M_OK' => tr('OK'),
                      'M_SUSPENDED' => tr('Suspended'),
                      'M_ERROR' => tr('Error'),

                      // selected area
                      'M_DOMAIN_NAME_SELECTED' => $domain_selected,
                      'M_CUSTOMER_ID_SELECTED' => $customerid_selected,
                      'M_LAST_NAME_SELECTED' => $lastname_selected,
                      'M_COMPANY_SELECTED' => $company_selected,
                      'M_CITY_SELECTED' => $city_selected,
                      'M_STATE_SELECTED' => $state_selected,
                      'M_COUNTRY_SELECTED' => $country_selected,
                      'M_ALL_SELECTED' => $all_selected,
                      'M_OK_SELECTED' => $ok_selected,
                      'M_SUSPENDED_SELECTED' => $suspended_selected,));
}

/**
 * Must be documented
 *
 * @param iMSCP_pTemplate $tpl iMSCP_pTemplate instance
 * @param  iMSCP_Database $db
 * @param $user_id
 * @param bool $encode
 * @return void
 */
function gen_purchase_haf($tpl, $db, $user_id, $encode = false)
{
     /** @var $cfg iMSCP_Config_Handler_File */
    $cfg = iMSCP_Registry::get('config');

    $query = "SELECT `header`, `footer` FROM `orders_settings` WHERE `user_id` = ?;";

    if (isset($_SESSION['user_theme'])) {
        $theme = $_SESSION['user_theme'];
    } else {
        $theme = $cfg->USER_INITIAL_THEME;
    }

    $rs = exec_query($db, $query, $user_id);

    if ($rs->recordCount() == 0) {
        $title = tr("i-MSCP - Order Panel");

        $header = <<<RIC
<?xml version="1.0" encoding="{THEME_CHARSET}" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>{$title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset={THEME_CHARSET}" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<link href="../themes/{$theme}/css/imscp_orderpanel.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div align="center">
			<table width="100%" style="height:95%">
				<tr>
					<td align="center">
RIC;

        $footer = <<<RIC
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
RIC;
    } else {
        $header = $rs->fields['header'];
        $footer = $rs->fields['footer'];
        $header = str_replace('\\', '', $header);
        $footer = str_replace('\\', '', $footer);
    }

    if ($encode) {
        $header = htmlentities($header, ENT_COMPAT, 'UTF-8');
        $footer = htmlentities($footer, ENT_COMPAT, 'UTF-8');
    }

    $tpl->assign(array(
                      'PURCHASE_HEADER' => $header,
                      'PURCHASE_FOOTER' => $footer));
}
