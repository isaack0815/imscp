<form data-transition="pop" name="admin_lod" method="post" action="admin_log.php">
<ul data-role="listview" data-dividertheme="a" data-theme="b">
	<li data-role="list-divider">{TR_CLEAR_LOG_MESSAGE}</li>
	<li><div class="ui-grid-a">
		<div class="ui-block-a">
			<select name="uaction_clear" data-theme="a">
				<option value="0" selected="selected">{TR_CLEAR_LOG_EVERYTHING}</option>
				<option value="2">{TR_CLEAR_LOG_LAST2}</option>
				<option value="4">{TR_CLEAR_LOG_LAST4}</option>
				<option value="12">{TR_CLEAR_LOG_LAST12}</option>
				<option value="26">{TR_CLEAR_LOG_LAST26}</option>
				<option value="52">{TR_CLEAR_LOG_LAST52}</option>
			</select>
		</div>
		<div class="ui-block-b">
			<input data-theme="a" name="submit" type="submit" value="{TR_CLEAR_LOG}"/>
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
	<li><div class="ui-grid-a">
		<!-- BDP: scroll_prev -->
		<div class="ui-block-b"><a data-transition="flip" data-role="button" data-icon="arrow-l" data-iconpos="left" href="admin_log.php?psi={PREV_PSI}" title="previous">previous</a></div>
		<!-- EDP: scroll_prev -->
		<!-- BDP: scroll_prev_gray -->
		<div class="ui-block-a"><a data-role="button" data-icon="arrow-l" data-iconpos="left" class="ui-disabled" href="#">previous</a></div>
		<!-- EDP: scroll_prev_gray -->
		<!-- BDP: scroll_next_gray -->
		<div class="ui-block-b"><a data-role="button" data-icon="arrow-r" data-iconpos="right" class="ui-disabled" href="#">next</a></div>
		<!-- EDP: scroll_next_gray -->
		<!-- BDP: scroll_next -->
		<div class="ui-block-b"><a data-transition="flip" data-role="button" data-icon="arrow-r" data-iconpos="right" href="admin_log.php?psi={NEXT_PSI}" title="next">next</a></div>
		<!-- EDP: scroll_next -->
	</div></li>
</ul>