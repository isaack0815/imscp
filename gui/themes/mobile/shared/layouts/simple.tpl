<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
	<title>{TR_PAGE_TITLE}</title>
	<meta name="robots" content="nofollow, noindex" />
	<meta http-equiv="Content-Type" content="text/html; charset={THEME_CHARSET}" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0-rc.1/jquery.mobile-1.1.0-rc.1.min.css" />
	<link rel="stylesheet" href="{THEME_COLOR_PATH}/css/imscp.css" />
	<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.1.0-rc.1/jquery.mobile-1.1.0-rc.1.min.js"></script>
</head>
<body>
	<div data-role="page" data-theme="a" data-content-theme="a">
		<div data-role="header">
			<h1>{productLongName}</h1>
		</div>
		<div data-role="content">
			<img src="{THEME_COLOR_PATH}/images/imscp_logo.png"/>
			<!-- BDP: page_message -->
			<div id="message" class="{MESSAGE_CLS}">{MESSAGE}</div>
			<!-- EDP: page_message -->
			<div class="body">
				{LAYOUT_CONTENT}
			</div>
		</div>
		<div data-role="footer">
			<a href="{productLink}" target="blank">{productCopyright}</a>
		</div>
	</div>
</body>
</html>
