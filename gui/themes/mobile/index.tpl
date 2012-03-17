		<div id="loginBox">
			<form data-transition="flip" name="loginFrm" action="index.php" method="post">
				<label for="uname">{TR_USERNAME}</label>
				<input type="text" name="uname" id="uname" tabindex="1"/>
				<label for="upass">{TR_PASSWORD}</label>
				<input type="password" name="upass" id="upass" tabindex="2"/>
				<div class="buttons">
					<button name="login" type="submit" tabindex="3">{TR_LOGIN}</button>

				</div>

			</form>
		</div>
		<div data-role="navbar">
			<ul>
				<!-- BDP: lostpwd_button -->
				<li><a href="lostpassword.php">{TR_LOSTPW}</a></li>
				<!-- EDP: lostpwd_button -->
				<!-- BDP: ssl_support -->
				<li><a href="{SSL_LINK}" title="{TR_SSL_DESCRIPTION}">{TR_SSL}</a></li>
				<!-- EDP: ssl_support -->
				<li><a href="{TR_PMA_LINK}" title="{TR_LOGIN_INTO_PMA}">{TR_PHPMYADMIN}</a></li>
				<li><a href="{TR_FTP_LINK}" title="{TR_LOGIN_INTO_FMANAGER}">FileManager</a></li>
				<li><a href="{TR_WEBMAIL_LINK}" title="{TR_LOGIN_INTO_WEBMAIL}">{TR_WEBMAIL}</a></li>
			</ul>
		</div>