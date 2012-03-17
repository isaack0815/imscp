<!DOCTYPE html>
<html dir="ltr" xml:lang="en">
<head>
	<title>{TR_PAGE_TITLE}</title>
	<meta http-equiv='Content-Script-Type' content='text/javascript' />
	<meta http-equiv='Content-Style-Type' content='text/css' />
	<meta http-equiv='Content-Type' content='text/html; charset={THEME_CHARSET}' />
	<meta name='copyright' content='i-MSCP' />
	<meta name='owner' content='i-MSCP' />
	<meta name='publisher' content='i-MSCP' />
	<meta name='robots' content='nofollow, noindex' />
	<meta name='title' content='{TR_PAGE_TITLE}' />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{THEME_COLOR_PATH}/css/jquery.mobile-1.1.0-rc.1.min.css" />
	<link rel="stylesheet" href="{THEME_COLOR_PATH}/css/imscp.css" />
	<script src="{THEME_COLOR_PATH}/js/jquery-1.7.1.min.js"></script>
	<script src="{THEME_COLOR_PATH}/js/jquery.mobile-1.1.0-rc.1.min.js"></script>
</head>
<body>
	<div data-role="page" data-theme="a" data-content-theme="a">
		<div id="header" data-role="header" data-position="fixed">
			<h1>{TR_SECTION_TITLE}</h1>
			<a data-icon="home" data-rel="dialog" data-transition="flip" href="menu.php" data-prefetch>Menu</a>
			<a data-icon="delete" data-transition="slide" href="../index.php?logout">{TR_MENU_LOGOUT}</a>
			<div class="ui-bar">
				<!-- BDP: logged_from -->
				<a data-icon="arrow-l" data-transition="slide" href="change_user_interface.php?action=go_back">{YOU_ARE_LOGGED_AS}</a>
				<!-- EDP: logged_from -->
				<!-- BDP: page_message -->
				<div id="message" class="{MESSAGE_CLS}">{MESSAGE}</div>
				<!-- EDP: page_message -->
			</div>
		</div>
		<div id="content" data-role="content">
			{LAYOUT_CONTENT}
		</div>
		<div id="footer" data-role="footer" data-position="fixed">
			<div data-role="collapsible">
				<h3>{TR_SECTION_TITLE}</h3>
				<!-- INCLUDE "../partials/navigation/left_menu.tpl" -->
			</div>
		</div>
	</div>
</body>
</html>
