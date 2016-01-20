<div class="headline">{$lang.event_regs}</div>

<script type="text/javascript">
	function search() {
		$("#divSearchResults").load("ajax_request.php?mod=events&file=events.reg.ajax&find="
			+$("#txtSearch").val()+"&eventid="+{$event.eventid});
	}
	function setReg(userid) {
		var reg;
		if ($("#reg_"+userid+":checked").val() == null) {
			reg = 0;
		}
		else {
			reg = 1;
		}
		$.get("ajax_request.php?mod=events&file=events.reg.ajax&userid="
			+ userid + "&reg=" + reg + "&eventid="+{$event.eventid});
	}
</script>

<p>
	{$lang.search}:
	<input type="text" name="txtSearch" value="{$search}" 
		id="txtSearch" style="width:350px;" onkeyup="search()" />
</p>
<div id="divSearchResults">

</div>