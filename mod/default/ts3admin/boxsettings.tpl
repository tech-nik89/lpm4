<!-- category and table styles -->
<style type="text/css">
	tr.row { height:24px; }
	tr.highlight_row { height:24px; }
	div.category, div.maxcategory {
		border:1px solid darkgrey;
		padding:2px 2px 2px 2px;
		width:500px;	
		margin-bottom:20px;
	}
	div.maxcategory {
		width:100%;	
	}
	div.categoryHead {
		position:relative;
		border:1px solid darkgrey;
		padding:2px 2px 2px 2px;	
		background-color:#lightgrey;
		font-weight:bold;
	}
	div.toggleCategoryHoder {
		position:absolute;
		top:2px;
		width:100%;
		text-align:right;
	}
	tr.selected_row td{
		background-color:lightblue;	
		height:24px;
	}
	
	a img{
		border:0px;	
	}
</style>

{if $ajaxcallback}
<script type="text/javascript">
	<!--{literal}
	reloadPage();
	function reloadPage() {
		self.location.reload();
	}
	{/literal}-->
</script>
{else}
<!-- Std. vars and functions -->
<script type="text/javascript">
	var loading = "{$lang.loading}";
	var imgsrc="{$imgsrc}";
	
	var settings = new Array();
	
	<!--{literal}
	function toggleCategory(catelement, fasthide) {
		var cat = catelement.getAttribute("cat");
		if(fasthide) {
			if(document.getElementById(cat+"tbl")!=null)$("#"+cat+"tbl").hide();
		}else{
			$("#"+cat+"tbl").fadeToggle("slow","swing");
		}
		var closed = catelement.getAttribute("closed");
		var imgelement = catelement.getElementsByTagName("img")[0];
		if(closed=="1") {
			catelement.setAttribute("closed","0");
			imgelement.src=imgsrc+"/toggle_minus.png";
		}else{
			catelement.setAttribute("closed","1");
			imgelement.src=imgsrc+"/toggle_plus.png";
		}
	}
	
	function saveBoxSettings() {
		var url = "ajax_request.php?mod=ts3admin&file=boxsettings.ajax&action=save";
		
		for(serverid in settings) {
			var vservers = settings[serverid]["vservers"];
			for(vserverid in vservers) {
				var element = document.getElementById("boxsettings"+serverid+"_"+vserverid+"_show");
				if(element!=null) {
					var show = element.checked?1:0;
					var join = element.checked?1:0;
					url += "&"+serverid+"_"+vserverid+"_show="+show;
					url += "&"+serverid+"_"+vserverid+"_join="+join;
				}
			}
		}
		$("#editboxsettings").load(url);
	}
	{/literal}-->
</script>

<div id="editboxsettings">
	<div class="headline">
		{$lang.serversshowninbox}
	</div>

	{foreach from=$server_settings item=settings}
	<div class="category">
		<script type="text/javascript">
		settings["{$settings.ID}"] = new Array();
		settings["{$settings.ID}"]["vservers"] = new Array();
		</script>
		<div class="categoryHead">{$settings.name} - {$settings.address}
			<div class="toggleCategoryHoder"><a href="" cat="boxsettings{$settings.ID}" id="aboxsettings{$settings.ID}" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
		</div>
		<table id="boxsettings{$settings.ID}tbl" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<th>{$lang.server}</th>
				<th style="text-align:center">{$lang.showinbox}</th>
				<th style="text-align:center">{$lang.showjoininbox}</th>
			</tr>
			
			{foreach from=$settings.vservers item=vservers}
			<script type="text/javascript">
			settings["{$settings.ID}"]["vservers"]["{$vservers.id}"] = {$vservers.id};
			</script>
			<tr class="{cycle values='highlight_row,row'}">
				<td>{$vservers.virtualserver_name}</td>
				<td style="text-align:center"><input type="checkbox" id="boxsettings{$settings.ID}_{$vservers.id}_show" {if $vservers.settings.show eq 1} checked {/if} /></td>
				<td style="text-align:center"><input type="checkbox" id="boxsettings{$settings.ID}_{$vservers.id}_join" {if $vservers.settings.join eq 1} checked {/if} /></td>
			</tr>
			{/foreach}
		</table>
	</div>
	{/foreach}
	<input type="submit" value="{$lang.save}" onclick="javascript: saveBoxSettings(); return false;"/>
</div>
{/if}
