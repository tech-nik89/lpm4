<div class="headline">{$room.title}</div>

{literal}
<script src="js/jquery.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

	var selectedX = 0, selectedY = 0;
	var mouseX = 0, mouseY = 0;

	jQuery(document).ready(function() {    
		$(document).mousemove(function(e)
		{       
			mouseX=e.pageX;
			mouseY=e.pageY;
		});  
	}) 
	
	function contentloader() {
		$("#searchresults").load("ajax_request.php?mod=room&file=userlist.ajax&eventid={/literal}{$room.eventid}{literal}&search_string="+$("#pU_username").val());
	}
	
	function showInfo(y, x, type, value) {
		{/literal}{if $show_coordinates}{literal}
			setPosition(x, y);
		{/literal}{/if}{literal}
	
		var show_tooltip='true';
		switch (type) {
			case 10: // empty Table
				tooltip_text = '{/literal}{$lang.table}{literal}';
				break;
			case 11: // full Table
			case 13: // reserved Table
				tooltip_text = '{/literal}{$lang.sitting_here}{literal}'+value;
				break;
			case 15: // Table
				tooltip_text = '{/literal}{$lang.staff_table}{literal}';
				break;
			case 1: // Entry
				tooltip_text = '{/literal}{$lang.entry}{literal}';
				break;
			case 2: // Info
				tooltip_text = '{/literal}{$lang.info}{literal}';
				break;
			case 3: // Network
				tooltip_text = '{/literal}{$lang.network}{literal}';
				break;
			case 4: // Emergency Exit
				tooltip_text = '{/literal}{$lang.emergency_exit}{literal}';
				break;
			case 6: // Bar
				tooltip_text = '{/literal}{$lang.bar}{literal}';
				break;
			case 7: // WC
				tooltip_text = '{/literal}{$lang.wc}{literal}';
				break;
			case 8: // WC M
				tooltip_text = '{/literal}{$lang.wc_m}{literal}';
				break;
			case 9: // WC F
				tooltip_text = '{/literal}{$lang.wc_f}{literal}';
				break;
			case 50: case 51: // Beamer
				tooltip_text = '{/literal}{$lang.beamer}{literal}';
				break;
			case 5: // Stairs
			case 40: case 41: // Stairs
				if(value.length>0)
					tooltip_text = value;	
				else
					show_tooltip='false';
				break;
			default: // any other
				show_tooltip='false';
				break;
		}
		if (show_tooltip=='true'){		
			var divInfo = document.getElementById("divInfo");
			
			selectedX = x;
			selectedY = y;
			
			var left = mouseX + 10;
			var top = mouseY;
			
			divInfo.style.left = left+'px';
			divInfo.style.top = top+'px';
			divInfo.style.position = 'absolute';
			divInfo.style.display = 'block';
			
			divInfo.innerHTML = tooltip_text;
		}
	}
	
	function showDiv(divName){	
		var divInfo = document.getElementById(divName);
		
		var left = mouseX + 10;
		var top = mouseY;
		
		divInfo.style.left = left+'px';
		divInfo.style.top = top+'px';
		divInfo.style.position = 'absolute';
		divInfo.style.display = 'block';
	}
	
	function showSitting(divName, y, x, url, text, postname, info){
		document.getElementById('sitdownform').action=url;
		document.getElementById('form_x').value=x;
		document.getElementById('form_y').value=y;
		document.getElementById('sittingsubmit').value=text;
		document.getElementById('postname').name=postname;
		document.getElementById('tableInfo').innerHTML=info;
		showDiv(divName);
	}
	
	function placeUser(y, x, text){		
		document.getElementById('pU_form_x').value=x;
		document.getElementById('pU_form_y').value=y;
		document.getElementById('pU_submit').value=text;
		showDiv('divPlaceUser');
	}
	
	function setUser(id, name){
		document.getElementById('pU_userid').value=id;
		document.getElementById('pU_username').value=name;
	}
	
	function hideDiv(divName){
		var divInfo = document.getElementById(divName);
		divInfo.style.display = 'none';
	}
	
	function setPosition(x, y){
		var coords = document.getElementById('coordinates');
		var xName = document.getElementById('x_axis_'+x).innerHTML;
		var yName = document.getElementById('y_axis_'+y).innerHTML;
		coords.innerHTML = yName+" "+xName;
	}
	
	
</script>
{/literal}

<div id="divInfo" style="display:none; position:absolute; padding:4px; border:1px solid #999999; background-color:#FFFFFF;">
	&nbsp;
</div>

<div id="divTable" style="display:none; position:absolute;">
	<div style="position:relative; float:right; background-color:#FFFFFF; border-top: 1pt solid #666666; border-left: 1pt solid #666666; border-right: 1pt solid #666666; cursor:pointer;" 
		onclick="hideDiv('divTable');">
		&nbsp;X&nbsp;
	</div>
	<div style="clear:both;">
	</div>
	<div id="divTableText" style="padding:3px 5px 5px 5px; border:1px solid #999999; background-color:#FFFFFF; text-align:center;">
		<div id="tableInfo" style="padding-bottom:4px;">
		</div>
		<form id="sitdownform" name="sitdownform" action="" method="POST">
			<input type="hidden" id="postname" name="" value="1"/>
			<input type="hidden" id="form_y" name="y" value=""/>
			<input type="hidden" id="form_x" name="x" value=""/>
			<input type="submit" id="sittingsubmit" name="submit" value=""/>
		</form>
	</div>
</div>

<div id="divPlaceUser" style="display:none; position:absolute;">
	<div style="position:relative; float:right; background-color:#FFFFFF; border-top: 1pt solid #666666; border-left: 1pt solid #666666; border-right: 1pt solid #666666; cursor:pointer;" 
		onclick="hideDiv('divPlaceUser');">
		&nbsp;X&nbsp;
	</div>
	<div style="clear:both;">
	</div>
	<div id="divTableText" style="padding:3px 5px 5px 5px; border:1px solid #999999; background-color:#FFFFFF; text-align:center;">
		<form id="place_User" name="place_User" action="" method="POST">
			{$lang.select_user}<br />
			<input type="name" id="pU_username" name="username" value="" onkeyup="contentloader();" autocomplete="off"/>
			<div id="searchresults">
			
			</div>
			<br />
			<input type="hidden" id="pU_postname" name="placeUser" value="1"/>
			<input type="hidden" id="pU_form_y" name="y" value=""/>
			<input type="hidden" id="pU_form_x" name="x" value=""/>
			<input type="hidden" id="pU_userid" name="userid" value=""/>
			<input type="submit" id="pU_submit" name="submit" value=""/>
		</form>
	</div>
</div>

{if $show_coordinates}
	<table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
		<tr>
			<td>
				{$lang.coordinates}:&nbsp; 
			</td>
			<td id="coordinates">
				0 0
			</td>
		</tr>
	</table>
<hr />
{/if}

{if $show_information}
	<table border="0" cellpadding="2" cellspacing="0" style="border-collapse:collapse;" width="40%">
		<colspan>
			<col width="25%">
			<col width="25%">
			<col width="25%">
			<col width="25%">
		</colspan>
		<tr>
			<td colspan="2">
				{$lang.tables_overall}:&nbsp; 
			</td>
			<td colspan="2" id="tables_free">
				{$table_information.overall}
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>{$lang.tables_free}</td>
			<td>{$lang.tables_full}</td>
			<td>{$lang.tables_staff}</td>
		</tr>
		<tr>
			<td>{$lang.all_rooms}</td>
			<td>{$table_information.all_rooms.free}</td>
			<td>{$table_information.all_rooms.full}</td>
			<td>{$table_information.all_rooms.staff}</td>
		</tr>
			<td>{$lang.this_room}</td>
			<td>{$table_information.this_room.free}</td>
			<td>{$table_information.this_room.full}</td>
			<td>{$table_information.this_room.staff}</td>
		</tr>
	</table>
<hr />
{/if}



<table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
	{if $show_coordinates}
		<tr>
			<td {if $grid_border}
					 style=" border:1px solid #999999;"
				{/if}
				>
				<div style="
					height:13px; width:13px; max-width:13px; max-height:13px; font-size:2.8em; align:center; vertical-align:center; overflow:hidden;
					">
					&nbsp;
				</div>
			</td>
			{section name=i loop=$columnindex}
				<td {if $grid_border}
						style="border:1px solid #999999;"
					{/if}
					>
					<div id="x_axis_{$smarty.section.i.index}"  style="
						height:13px; width:13px; max-width:13px; max-height:13px; font-size:10px; align:center; vertical-align:center; overflow:hidden;
						">
						{$columnindex[i]}
					</div>
				</td>
			{/section}
		</tr>
	{/if}
{section name=y loop=$matrix}
	<tr>
		{if $show_coordinates}
			<td {if $grid_border}
					style="border:1px solid #999999;" 
				{/if}
				>
				<div id="y_axis_{$smarty.section.y.index}"  style="height:13px; width:13px; max-width:13px; max-height:13px; overflow:hidden; font-size:10px; align:center; vertical-align:center;">
					{$rowindex[y]}
				</div>
			</td>
		{/if}
	{section name=x loop=$matrix[y]}
		<td {if $grid_border}style="border:1px solid #999999;"{/if}>
			<div style="background-image:url({$matrix[y][x].img}); height:13px; width:13px;
				{if $matrix[y][x].clickable}
					cursor:pointer;"
					{if $matrix[y][x].showemptytable}
						{if $remove_and_add_users}
							onclick="placeUser({$matrix[y][x].y}, {$matrix[y][x].x}, '{$lang.place_user}');"	
						{else}
							{if $only_reserving}
								onclick="showSitting('divTable', {$matrix[y][x].y}, {$matrix[y][x].x}, '{$matrix[y][x].url}', '{$lang.reserve}', 'reserve', '{if $reserved_allready}{$lang.sitting_already}{/if}');"	
							{/if}
							{if $allow_sitting}
								onclick="showSitting('divTable', {$matrix[y][x].y}, {$matrix[y][x].x}, '{$matrix[y][x].url}', '{$lang.sit_down}', 'sitdown', '{if $sitting_allready}{$lang.sitting_already}{/if}');"	
							{/if}
						{/if}
					{/if}
					{if $matrix[y][x].ihavereservedhere}
						{if $allow_sitting}
							onclick="showSitting('divTable', {$matrix[y][x].y}, {$matrix[y][x].x}, '{$matrix[y][x].url}', '{$lang.sit_down}', 'sitdown', '');"	
						{else}
							onclick="showSitting('divTable', {$matrix[y][x].y}, {$matrix[y][x].x}, '{$matrix[y][x].url}', '{$lang.reserve_undo}', 'unreserve', '');"	
						{/if}
					{/if}
					{if $matrix[y][x].imsittinghere}
						onclick="showSitting('divTable', {$matrix[y][x].y}, {$matrix[y][x].x}, '{$matrix[y][x].url}', '{$lang.stand_up}', 'standup', '');"	
					{/if}
	
					{if $matrix[y][x].onlyshowurl}
						{if $remove_and_add_users AND $matrix[y][x].remove_user}
							onclick="showSitting('divTable', {$matrix[y][x].y}, {$matrix[y][x].x}, '', '{$lang.remove_user}', 'remove', '');"	
						{else}
							onclick="location.href='{$matrix[y][x].url}'"	
						{/if}
					{/if}
				{else}
					"
				{/if}
				onmousemove="showInfo({$matrix[y][x].y}, {$matrix[y][x].x}, {$matrix[y][x].type}, '{$matrix[y][x].value}');"
				onmouseout="hideDiv('divInfo');"
			>
			</div>
		</td>
	{/section}
	</tr>
{/section}
</table>
<br />
<div>{$info_seating}</div>
