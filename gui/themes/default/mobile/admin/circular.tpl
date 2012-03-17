<form name="admin_email_setup" method="post" action="circular.php">
<ul data-role="listview" data-dividertheme="a" data-theme="b">
	<li data-role="list-divider">{TR_CORE_DATA}</li>
	<li>
		<label for="rcpt_to">{TR_SEND_TO}</label>
		<select id="rcpt_to" name="rcpt_to">
			<option value="usrs">{TR_ALL_USERS}</option>
			<option value="rsls">{TR_ALL_RESELLERS}</option>
			<option value="usrs_rslrs">{TR_ALL_USERS_AND_RESELLERS}</option>
		</select>

		<label for="msg_subject">{TR_MESSAGE_SUBJECT}</label>
		<input class="inputTitle" type="text" name="msg_subject" id="msg_subject" value="{MESSAGE_SUBJECT}"/>

		<label for="msg_text">{TR_MESSAGE_TEXT}</label>
		<textarea name="msg_text" id="msg_text">{MESSAGE_TEXT}</textarea>
	</li>
	<li data-role="list-divider">{TR_ADDITIONAL_DATA}</li>
	<li>
		<label for="sender_email">{TR_SENDER_EMAIL}</label>
		<input type="text" name="sender_email" id="sender_email" value="{SENDER_EMAIL}"/>

		<label for="sender_name">{TR_SENDER_NAME}</label>
		<input type="text" name="sender_name" id="sender_name" value="{SENDER_NAME}"/>

		<input data-theme="e" name="submit" type="submit" value="{TR_SEND_MESSAGE}"/>
		<input type="hidden" name="uaction" value="send_circular"/>
	</li>
</div>
</ul>
</form>