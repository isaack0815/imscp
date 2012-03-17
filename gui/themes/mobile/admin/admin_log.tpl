<form name="admin_lod" method="post" action="admin_log.php">
<ul data-role="listview" data-dividertheme="a" data-theme="b">
	<li data-role="list-divider">{TR_CLEAR_LOG_MESSAGE}</li>
	<li><div class="ui-grid-a">
		<div class="ui-block-a">
			<select name="uaction_clear" id="uaction_clear">
				<option value="0" selected="selected">{TR_CLEAR_LOG_EVERYTHING}</option>
				<option value="2">{TR_CLEAR_LOG_LAST2}</option>
				<option value="4">{TR_CLEAR_LOG_LAST4}</option>
				<option value="12">{TR_CLEAR_LOG_LAST12}</option>
				<option value="26">{TR_CLEAR_LOG_LAST26}</option>
				<option value="52">{TR_CLEAR_LOG_LAST52}</option>
			</select>
		</div>
		<div class="ui-block-b">
			<input name="submit" type="submit" value="{TR_CLEAR_LOG}"/>
		</div>
		<input type="hidden" name="uaction" value="clear_log"/>
	</div></li>
</ul>
</form>
<ul data-role="listview" data-dividertheme="a" data-theme="b">
	<li data-role="list-divider"><div class="ui-grid-a">
		<div class="ui-block-a">{TR_DATE}</div>
		<div class="ui-block-b">{TR_MESSAGE}</div>
	</div></li>
	<!-- BDP: log_row -->
	<li><div class="ui-grid-a">
		<div class="ui-block-a {ROW_CLASS}">{DATE}</div>
		<div class="ui-block-b {ROW_CLASS}">{MESSAGE}</div>
	</div></li>
	<!-- EDP: log_row -->
</ul>