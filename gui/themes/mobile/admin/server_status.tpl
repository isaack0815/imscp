<!-- BDP: props_list -->
<ul data-role="listview" data-dividertheme="a" data-theme="b">
	<li data-role="list-divider"><div class="ui-grid-a">
		<div class="ui-block-a">{TR_HOST}</div>
		<div class="ui-block-b">{TR_SERVICE} <span class="ui-li-count">{TR_STATUS}</span></div>
	</div></li>
	<!-- BDP: service_status -->
	<li><div class="ui-grid-a">
		<div class="ui-block-a">{HOST}:{PORT}</div>
		<div class="ui-block-b">{SERVICE} <span class="ui-li-count {CLASS}">{STATUS}</span></div>
	</div></li>
	<!-- EDP: service_status -->
</ul>
<!-- EDP: props_list -->
