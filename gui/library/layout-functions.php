<?php
/**
 * i-MSCP - internet Multi Server Control Panel
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
 * @category	iMSCP
 * @package		iMSCP_Core
 * @subpackage	Layout
 * @copyright	2001-2006 by moleSoftware GmbH
 * @copyright	2006-2010 by ispCP | http://isp-control.net
 * @copyright	2010-2012 by i-MSCP | http://i-mscp.net
 * @link		http://i-mscp.net
 * @author		ispCP Team
 * @author		i-MSCP Team
 */

/**
 * Must be documented.
 *
 * @param  $user_id
 * @return array
 * @todo must be removed
 */
function get_user_gui_props($user_id)
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	$query = "SELECT `lang`, `layout` FROM `user_gui_props` WHERE `user_id` = ?";
	$stmt = exec_query($query, $user_id);

	if ($stmt->recordCount() == 0 ||
		(empty($stmt->fields['lang']) && empty($stmt->fields['layout']))
	) {
		// values for user id, some default stuff
		return array($cfg->USER_INITIAL_LANG, $cfg->USER_INITIAL_THEME);
	} elseif (empty($stmt->fields['lang'])) {
		return array($cfg->USER_INITIAL_LANG, $stmt->fields['layout']);
	} elseif (empty($stmt->fields['layout'])) {
		return array($stmt->fields['lang'], $cfg->USER_INITIAL_THEME);
	} else {
		return array($stmt->fields['lang'], $stmt->fields['layout']);
	}
}

/**
 * Generates the page messages to display on client browser.
 *
 * Note: The default level for message is sets to 'info'.
 * See the {@link set_page_message()} function for more information.
 *
 * @param  iMSCP_pTemplate $tpl iMSCP_pTemplate instance
 * @return void
 */
function generatePageMessage($tpl)
{
	$namespace = new Zend_Session_Namespace('pageMessages');

	if (Zend_Session::namespaceIsset('pageMessages')) {
		foreach (array('success', 'error', 'warning', 'info') as $level) {
			if (isset($namespace->{$level})) {
				$tpl->assign(
					array(
						'MESSAGE_CLS' => $level .
							(($level == 'success') ? ' timeout' : ''),
						'MESSAGE' => $namespace->{$level}));

				$tpl->parse('PAGE_MESSAGE', '.page_message');
			}
		}

		Zend_Session::namespaceUnset('pageMessages');
	} else {
		$tpl->assign('PAGE_MESSAGE', '');
	}
}

/**
 * Sets a page message to display on client browser.
 *
 * @param string $message $message Message to display
 * @param string $level Message level (INFO, WARNING, ERROR, SUCCESS)
 * @return void
 */
function set_page_message($message, $level = 'info')
{
	$level = strtolower($level);

	if (!is_string($message)) {
		throw new iMSCP_Exception('set_page_message() expects a string for $message');
	} elseif (!in_array($level, array('info', 'warning', 'error', 'success'))) {
		throw new iMSCP_Exception('Wrong level for page message.');
	}

	static $namespace = null;

	if (null === $namespace) {
		$namespace = new Zend_Session_Namespace('pageMessages');
	}

	if (isset($namespace->{$level})) {
		$namespace->{$level} .= "\n<br />$message";
	} else {
		$namespace->{$level} = $message;
	}
}

/**
 * format message(s) to be displayed on client browser as page message.
 *
 * @param  string|array $messages Message or stack of messages to be concatenated
 * @return string Concatenated messages
 */
function format_message($messages)
{
	$string = '';

	if (is_array($messages)) {
		foreach ($messages as $message) {
			$string .= $message . "<br />\n";
		}
	} elseif (is_string($messages)) {
		$string = $messages;
	} else {
		throw new iMSCP_Exception('set_page_message() expects a string or an array for $messages.');
	}

	return $string;
}

/**
 * Gets menu variables.
 *
 * @param  string $menu_link Menu link
 * @return mixed
 */
function get_menu_vars($menu_link)
{
	if (strpos($menu_link, '}') === false || strpos($menu_link, '}') === false) {
		return $menu_link;
	}

	$query = "
		SELECT
			`customer_id`, `fname`, `lname`, `firm`, `zip`, `city`, `state`,
			`country`, `email`, `phone`, `fax`, `street1`, `street2`
		FROM
			`admin`
		WHERE
			`admin_id` = ?
	";
	$stmt = exec_query($query, $_SESSION['user_id']);

	$search = array();
	$replace = array();

	$search [] = '{uid}';
	$replace[] = $_SESSION['user_id'];
	$search [] = '{uname}';
	$replace[] = tohtml($_SESSION['user_logged']);
	$search [] = '{cid}';
	$replace[] = tohtml($stmt->fields['customer_id']);
	$search [] = '{fname}';
	$replace[] = tohtml($stmt->fields['fname']);
	$search [] = '{lname}';
	$replace[] = tohtml($stmt->fields['lname']);
	$search [] = '{company}';
	$replace[] = tohtml($stmt->fields['firm']);
	$search [] = '{zip}';
	$replace[] = tohtml($stmt->fields['zip']);
	$search [] = '{city}';
	$replace[] = tohtml($stmt->fields['city']);
	$search [] = '{state}';
	$replace[] = $stmt->fields['state'];
	$search [] = '{country}';
	$replace[] = tohtml($stmt->fields['country']);
	$search [] = '{email}';
	$replace[] = tohtml($stmt->fields['email']);
	$search [] = '{phone}';
	$replace[] = tohtml($stmt->fields['phone']);
	$search [] = '{fax}';
	$replace[] = tohtml($stmt->fields['fax']);
	$search [] = '{street1}';
	$replace[] = tohtml($stmt->fields['street1']);
	$search [] = '{street2}';
	$replace[] = tohtml($stmt->fields['street2']);

	$query = "
		SELECT
			`domain_name`, `domain_admin_id`
		FROM
			`domain`
		WHERE
			`domain_admin_id` = ?
	";
	$stmt = exec_query($query, $_SESSION['user_id']);

	$search [] = '{domain_name}';
	$replace[] = $stmt->fields['domain_name'];

	return str_replace($search, $replace, $menu_link);
}

/**
 * Returns available color set for current layout.
 *
 * @author Laurent Declercq <l.declercq@nuxwin.com>
 * @since i-MSCP 1.0.1.6
 * @return array
 */
function layout_getAvailableColorSet()
{
	// TODO parse current layout directory to found available color set.
	return array('black', 'blue', 'green', 'red', 'yellow');
}

/**
 * Returns layout color for given user.
 *
 * @author Laurent Declercq <l.declercq@nuxwin.com>
 * @since i-MSCP 1.0.1.6
 * @param int $userId user unique identifier
 * @return string User layout color
 */
function layout_getUserLayoutColor($userId)
{
	$allowedColors = layout_getAvailableColorSet();

	$query = 'SELECT `layout_color` FROM `user_gui_props` WHERE `user_id` = ?';
	$stmt = exec_query($query, (int)$userId);

	if ($stmt->rowCount()) {
		$color = $stmt->fields['layout_color'];

		if (!$color || !in_array($color, $allowedColors)) {
			$color = array_shift($allowedColors);
		}
	} else {
		$color = array_shift($allowedColors);
	}

	return $color;
}

/**
 * Set layout color.
 *
 * @author Laurent Declercq <l.declercq@nuxwin.com>
 * @since i-MSCP 1.0.1.6
 * @param iMSCP_Events_Event $event
 * @return void
 * @todo Use cookies to store user UI properties (Remember me implementation?)
 */
function layout_setColor($event)
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	if (isset($_SESSION['user_theme_color'])) {
		$color = $_SESSION['user_theme_color'];
	} elseif (isset($_SESSION['user_id'])) {
		$userId = isset($_SESSION['logged_from_id']) ? $_SESSION['logged_from_id'] : $_SESSION['user_id'];
		$color = layout_getUserLayoutColor($userId);
		$_SESSION['user_theme_color'] = $color;
	} else {
		$colors = layout_getAvailableColorSet();
		$color = array_shift($colors);
	}

	/** @var $tpl iMSCP_pTemplate */
	$tpl = $event->getParam('templateEngine');

	$tpl->assign(
		array(
			'THEME_COLOR_PATH' => '/themes/' . $cfg->USER_INITIAL_THEME, // @TODO Move this statement
			'THEME_COLOR' => $color));

	$tpl->parse('LAYOUT', 'layout');
}

/**
 * Sets given layout color for given user.
 *
 * @author Laurent Declercq <l.declercq@nuxwin.com>
 * @since i-MSCP 1.0.1.6
 * @param int $userId User unique identifier
 * @param string $color Layout color
 * @return bool TRUE on success false otherwise
 */
function layout_setUserLayoutColor($userId, $color)
{
	if (in_array($color, layout_getAvailableColorSet())) {
		$query = 'UPDATE `user_gui_props` SET `layout_color` = ? WHERE `user_id` = ?';
		exec_query($query, array($color, (int)$userId));

		// Dealing with sessions across multiple browsers for same user identifier - Begin

		$sessionId = session_id();

		$query = "SELECT `session_id` FROM `login` WHERE `user_name` = ?  AND `session_id` <> ?";
		$stmt = exec_query($query, array($_SESSION['user_logged'], $sessionId));

		if ($stmt->rowCount()) {
			foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $otherSessionId) {
				session_write_close();
				session_id($otherSessionId);
				session_start();
				$_SESSION['user_theme_color'] = $color; // Update user layout color
			}

			// Return back to the previous session
			session_write_close();
			session_id($sessionId);
			session_start();
		}

		// Dealing with data across multiple sessions - End

		return true;
	}

	return false;
}

/**
 * Get user logo path.
 *
 * Note: Only administrators and resellers can have their own logo. Search is done in
 * the following order: user logo -> user's creator logo -> theme logo --> isp logo.
 *
 * @author Laurent Declercq <l.declercq@nuxwin.com>
 * @since i-MSCP 1.0.1.4
 * @param bool $searchForCreator Tell whether or not search must be done for user's
 *							   creator in case no logo is found for user
 * @param bool $returnDefault	Tell whether or not default logo must be returned
 * @return string User logo path.
 * @todo cache issues
 */
function layout_getUserLogo($searchForCreator = true, $returnDefault = true)
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	// On switched level, we want show logo from logged user
	if (isset($_SESSION['logged_from_id']) && $searchForCreator) {
		$userId = $_SESSION['logged_from_id'];
		// Customers inherit the logo of their reseller
	} elseif ($_SESSION['user_type'] == 'user') {
		$userId = $_SESSION['user_created_by'];
	} else {
		$userId = $_SESSION['user_id'];
	}

	$query = 'SELECT `logo` FROM `user_gui_props` WHERE `user_id`= ?';
	$stmt = exec_query($query, $userId);

	// No logo is found for the user, let see for it creator
	if ($searchForCreator && $userId != 1 && empty($stmt->fields['logo'])) {
		$query = '
            SELECT
                `b`.`logo`
            FROM
                `admin` `a`
            LEFT JOIN
                `user_gui_props` `b` ON (`b`.`user_id` = `a`.`created_by`)
            WHERE
                `a`.`admin_id`= ?
        ';
		$stmt = exec_query($query, $userId);
	}

	// No  user logo found
	if (empty($stmt->fields['logo']) ||
		!file_exists($cfg->GUI_ROOT_DIR . '/data/ispLogos/' . $stmt->fields['logo'])
	) {
		if (!$returnDefault) {
			return '';
		} elseif (file_exists($cfg->GUI_ROOT_DIR . '/public/themes/' .
			$_SESSION['user_theme'] . '/images/imscp_logo.png')
		) {
			return '../themes/' . $_SESSION['user_theme'] . '/images/imscp_logo.png';
		} else {
			// no logo available, we are using default
			return $cfg->ISP_LOGO_PATH . '/' . 'isp_logo.gif';
		}
	}

	return $cfg->ISP_LOGO_PATH . '/' . $stmt->fields['logo'];
}

/**
 * Updates user logo.
 *
 * Note: Only administrators and resellers can have their own logo.
 *
 * @author Laurent Declercq <l.declercq@nuxwin.com>
 * @since i-MSCP 1.0.1.4
 * @return bool TRUE on success, FALSE otherwise
 */
function layout_updateUserLogo()
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	// closure that is run before move_uploaded_file() function - See the
	// Utils_UploadFile() function for further information about implementation
	// details
	$beforeMove = function($cfg)
	{
		$tmpFilePath = $_FILES['logoFile']['tmp_name'];

		// Checking file mime type
		if (!($fileMimeType = checkMimeType($tmpFilePath, array('image/gif',
			'image/jpeg',
			'image/pjpeg',
			'image/png')))
		) {
			set_page_message(tr('You can only upload images.'), 'error');
			return false;
		}

		// Retrieving file extension (gif|jpeg|png)
		if ($fileMimeType == 'image/pjpeg' || $fileMimeType == 'image/jpeg') {
			$fileExtension = 'jpeg';
		} else {
			$fileExtension = substr($fileMimeType, -3);
		}

		// Getting the image size
		list($imageWidth, $imageHeigth) = getimagesize($tmpFilePath);

		// Checking image size
		if ($imageWidth > 500 || $imageHeigth > 90) {
			set_page_message(tr('Images have to be smaller than 500 x 90 pixels.'), 'error');
			return false;
		}

		// Building an unique file name
		$fileName = sha1(utils_randomString(15) . '-' . $_SESSION['user_id']) .
			'.' . $fileExtension;

		// Return destination file path
		return $cfg->GUI_ROOT_DIR . '/data/ispLogos/' . $fileName;
	};

	if (($logoPath = utils_uploadFile('logoFile', array($beforeMove, $cfg))) === false) {
		return false;
	} else {
		if ($_SESSION['user_type'] == 'admin') {
			$userId = 1;
		} else {
			$userId = $_SESSION['user_id'];
		}

		// We must catch old logo before update
		$oldLogoFile = layout_getUserLogo(false, false);

		$query = "UPDATE `user_gui_props` SET `logo` = ? WHERE `user_id` = ?";
		exec_query($query, array(basename($logoPath), $userId));

		// Deleting old logo (we are safe here) - We don't return FALSE on failure .
		// The administrator will be warned through logs.
		layout_deleteUserLogo($oldLogoFile, true);
	}

	return true;
}

/**
 * Deletes user logo.
 *
 * @author Laurent Declercq <l.declercq@nuxwin.com>
 * @since i-MSCP 1.0.1.4
 * @param string $logoFilePath OPTIONAL Logo file path
 * @param bool $onlyFile OPTIONAL Tell whether or not only logo file must be deleted
 * @return bool TRUE on success, FALSE otherwise
 */
function layout_deleteUserLogo($logoFilePath = null, $onlyFile = false)
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	if (null === $logoFilePath) {
		if ($_SESSION['user_type'] == 'admin') {
			$logoFilePath = layout_getUserLogo(true);
		} else {
			$logoFilePath = layout_getUserLogo(false);
		}
	}

	if ($_SESSION['user_type'] == 'admin') {
		$userId = 1;
	} else {
		$userId = $_SESSION['user_id'];
	}

	if (!$onlyFile) {
		$query = "UPDATE `user_gui_props` SET `logo` = ? WHERE `user_id` = ?";
		exec_query($query, array(null, $userId));
	}

	if (strpos($logoFilePath, $cfg->ISP_LOGO_PATH) !== false) {
		$logoFilePath = $cfg->GUI_ROOT_DIR . '/data/ispLogos/' . basename($logoFilePath);

		if (file_exists($logoFilePath) && @unlink($logoFilePath)) {
			return true;
		} else {
			write_log(tr("System is unable to remove '%s' user logo.", $logoFilePath), E_USER_WARNING);
			return false;
		}
	}

	return true;
}

/**
 * Is user logo?
 *
 * @author Laurent Declercq <l.declercq@nuxwin.com>
 * @since i-MSCP 1.0.1.4
 * @param string $logoPath Logo path to match against
 * @return bool TRUE if $logoPath is an user's logo, FALSE otherwise
 */
function layout_isUserLogo($logoPath)
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	if ($logoPath == '../themes/' . $_SESSION['user_theme'] . '/images/imscp_logo.png'
		|| $logoPath == $cfg->ISP_LOGO_PATH . '/' . 'isp_logo.gif'
	) {
		return false;
	}

	return true;
}

/**
 * Load navigation file for current UI level.
 *
 * @author Laurent Declercq <l.declercq@nuxwin.com>
 * @since i-MSCP 1.0.1.6
 * @param iMSCP_Events_Event $event
 * @return void
 */
function layout_LoadNavigation($event)
{
	if (isset($_SESSION['user_type'])) {
		/** @var $cfg iMSCP_Config_Handler_File */
		$cfg = iMSCP_Registry::get('config');

		switch ($_SESSION['user_type']) {
			case 'admin':
				$menuPath = "{$cfg->ROOT_TEMPLATE_PATH}/admin/navigation.xml";
				break;
			case 'reseller':
				$menuPath = "{$cfg->ROOT_TEMPLATE_PATH}/reseller/navigation.xml";
				break;
			default:
				$menuPath = "{$cfg->ROOT_TEMPLATE_PATH}/client/navigation.xml";
		}

		iMSCP_Registry::set('navigation', new Zend_Navigation(new Zend_Config_Xml($menuPath, 'navigation')));

		// Set main menu labels visibility for the current environment
		iMSCP_Events_Manager::getInstance()->registerListener(
			iMSCP_Events::onBeforeGenerateNavigation, 'layout_setMainMenuLabelsVisibilityEvt'
		);
	}
}

/**
 * Tells whether or not main menu labels are visible for the given user.
 *
 * @param int $userId User unique identifier
 * @return bool
 */
function layout_isMainMenuLabelsVisible($userId)
{
	$query = 'SELECT `show_main_menu_labels` FROM `user_gui_props` WHERE `user_id` = ?';
	$stmt = exec_query($query, (int)$userId);

	if ($stmt->rowCount()) {
		return (bool) $stmt->fields['show_main_menu_labels'];
	}

	return true;
}

/**
 * Sets main menu label visibility for the given user.
 *
 * @param int $userId User unique identifier
 * @param int $visibility (0|1)
 * @return void
 */
function layout_setMainMenuLabelsVisibility($userId, $visibility)
{
	$visibility = (int) $visibility;

    $query = 'UPDATE `user_gui_props` SET `show_main_menu_labels` = ? WHERE `user_id` = ?';
    exec_query($query, array($visibility, (int)$userId));

    if (!isset($_SESSION['logged_from_id'])) {
       $_SESSION['show_main_menu_labels'] = $visibility;
	}
}

/**
 * Sets main menu visibility for current environment.
 *
 * @param $event iMSCP_Events_Event
 * @return void
 */
function layout_setMainMenuLabelsVisibilityEvt($event)
{
    if (!isset($_SESSION['show_main_menu_labels']) && isset($_SESSION['user_type'])) {
        $userId = isset($_SESSION['logged_from_id']) ? $_SESSION['logged_from_id'] : $_SESSION['user_id'];
        $_SESSION['show_main_menu_labels'] =  layout_isMainMenuLabelsVisible($userId);
    }
}

// I'm ok to put that here
function layout_isMobileBrowser()
{
	$useragent=$_SERVER['HTTP_USER_AGENT'];
	return preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
}
