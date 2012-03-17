<ul data-role="listview" data-dividertheme="a" data-theme="b">
	<li data-role="list-divider">{TR_ADMINISTRATORS}</li>
	<!-- BDP: admin_message -->
	<li>{ADMIN_MESSAGE}</li>
	<!-- EDP: admin_message -->
	<!-- BDP: admin_list -->
	<li data-role="collapsible-set">
		<!-- BDP: admin_item -->
		<div data-role="collapsible" data-theme="b">
			<h3>{ADMIN_USERNAME}</h3>
			<ul data-role="listview" data-theme="none">
				<li>
					{TR_CREATED_ON}
					<span class="ui-li-count">{ADMIN_CREATED_ON}</span>
				</li>
				<li>
					{TR_ADMIN_CREATED_BY}
					<span class="ui-li-count">{ADMIN_CREATED_BY}</span>
				</li>
				<li><div>
					<!--<a data-role="button" data-inline="true" data-theme="e" href="{URL_EDIT_ADMIN}">{TR_EDIT}</a>-->
					<!-- BDP: admin_delete_link -->
					<a data-role="button" data-inline="true" data-theme="e" href="{URL_DELETE_ADMIN}" rel="external" onclick="return confirm(sprintf('{TR_MESSAGE_DELETE}', '{ADMIN_USERNAME}'))" class="link">{TR_DELETE}</a>
					<!-- EDP: admin_delete_link -->
				</div></li>
			</ul>
		</div>
		<!-- EDP: admin_item -->
	</li>
	<!-- EDP: admin_list -->

	<li data-role="list-divider">{TR_RESELLERS}</li>
	<!-- BDP: rsl_message -->
	<li>{RSL_MESSAGE}</li>
	<!-- EDP: rsl_message -->
	<!-- BDP: rsl_list -->
	<li data-role="collapsible-set">
		<!-- BDP: rsl_item -->
		<div data-role="collapsible" data-theme="b">
			<h3>{RSL_USERNAME}</h3>
			<ul data-role="listview" data-theme="none">
				<li>
				{TR_CREATED_ON}
					<span class="ui-li-count">{RESELLER_CREATED_ON}</span>
				</li>
				<li>
				{TR_RSL_CREATED_BY}
					<span class="ui-li-count">{RSL_CREATED_BY}</span>
				</li>
				<li><div>
					<a data-role="button" data-inline="true" data-theme="e" rel="external" href="{URL_CHANGE_INTERFACE}">{GO_TO_USER_INTERFACE}</a>
					<!--<a data-role="button" data-inline="true" data-theme="e" href="{URL_EDIT_RSL}">{TR_EDIT}</a>-->
					<a data-role="button" data-inline="true" data-theme="e" href="{URL_DELETE_RSL}" rel="external" onclick="return confirm(sprintf('{TR_MESSAGE_DELETE}', '{RSL_USERNAME}'))" class="link">{TR_DELETE}</a>
				</div></li>
			</ul>
		</div>
		<!-- EDP: rsl_item -->
	</li>
</ul>