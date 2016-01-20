<div style="position:relative">
	<div>
	{if $manage_box_allowed}
	<a href="ajax_request.php?mod=ts3admin&file=boxsettings.ajax" id="boxSettingsA" ><img src="{$imgsrc}/settings.png" /></a>
	{/if}
	</div>
	
	<div id="ts3serverinfo_box"></div>
</div>

<script type="text/javascript">
function RefreshTs3ServerList() {
	$("#ts3serverinfo_box").load("ajax_request.php?mod=ts3admin&file=serverboxinfo.ajax");
}
$("#boxSettingsA").fancybox();
RefreshTs3ServerList();
window.setInterval('RefreshTs3ServerList()',20000);
</script>