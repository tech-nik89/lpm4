<div class="headline">{$lang.edit_items}: {$room.title}</div>
{literal}
<script language="javascript" type="text/javascript">
	
	var selectedType = 0;
	var selectedPath = '{/literal}{$image_white}{literal}';
	
	var selectedX = 0, selectedY = 0;
	var mouseX = 0, mouseY = 0;
	
	document.onmousemove = function(e) {
		mouseX = e.pageX || event.clientX + document.body.scrollLeft;
		mouseY = e.pageY || event.clientY + document.body.scrollTop;
	};

	function showInfo(type)
	{	
		var show_tooltip='true';
		switch (type)
		{
			case 10: // Table
				tooltip_text = '{/literal}{$lang.table}{literal}';
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
			case 40: case 41: // Door
				tooltip_text = '{/literal}{$lang.door}{literal}';
				break;
			case 50: case 51: // Beamer
				tooltip_text = '{/literal}{$lang.beamer}{literal}';
				break;
			case 60: case 61: // Fenster
				tooltip_text = '{/literal}{$lang.window}{literal}';
				break;
			default: // any other
				show_tooltip='false';
				break;
		}
		if (show_tooltip=='true')
		{		
			var divInfo = document.getElementById("divInfo");

			var left = mouseX + 10;
			var top = mouseY;
			
			divInfo.style.left = left+'px';
			divInfo.style.top = top+'px';
			divInfo.style.position = 'absolute';
			divInfo.style.display = 'block';
			
			divInfo.innerHTML = tooltip_text;
		}
	}
	
	function hideDiv(id)
	{
		var divToHide= document.getElementById(id);
		divToHide.style.display = 'none';
	}
	
	function showSelectItem()
	{
		var selectItemMenu = document.getElementById("selectItem");
	
		selectItemMenu.style.left = mouseX+'px';
		selectItemMenu.style.top = mouseY+'px';
		selectItemMenu.style.position = 'absolute';
		selectItemMenu.style.display = 'block';
	}

	function selectItem(itemType, imagePath)
	{
		//Unmark last selected
		var lastSelImgType = document.getElementById("selected_"+selectedType);
		lastSelImgType.style.border="2pt solid white";
		
		//Mark selected
		var selImgType = document.getElementById("selected_"+itemType);
		selImgType.style.border="2pt solid black";
		
		//Put type in stack
		selectedType = itemType;
		selectedPath = imagePath;
	}
	
	function paintTile(y, x)
	{
		var hdnFieldTarget = document.getElementById("field_"+y+"_"+x);
		var image = document.getElementById("img_"+y+"_"+x);
		
		selectedY = y;
		selectedX = x;
		
		if(selectedType==5)  // Stairs
		{
			showSelectRoom();
		}
		if(selectedType==40 || selectedType==41)  // Doors
		{
			showSelectRoom();
		}
		hdnFieldTarget.value = selectedType;
		image.src = selectedPath;
	}
	
	function showSelectRoom()
	{
		var divRooms = document.getElementById("divRoomsDropDown");
				
		var left = mouseX;
		var top = mouseY;
			
		divRooms.style.left = left+'px';
		divRooms.style.top = top+'px';
		divRooms.style.position = 'absolute';
		divRooms.style.display = 'block';
	}
	
	function validateSelectRoom()
	{
		var roomList = document.getElementById("roomSelect");
		var roomnr = roomList.options[roomList.selectedIndex].value;
		
		var hdnFieldValue = document.getElementById("field_"+selectedY+"_"+selectedX+"_value");
		hdnFieldValue.value = roomnr;

		hideDiv('divRoomsDropDown');
	}
	
	function unsetAll(height, width, imgPath)
	{
		var sure = confirm(unescape('{/literal}{$lang.items_really_delete}{literal}'));
		if(sure)
		{
			for(var x=0; x<height; x++)
			{
				for(var y=0; y<width; y++)
				{
					var hdnFieldTarget = document.getElementById("field_"+x+"_"+y);
					var hdnFieldValue = document.getElementById("field_"+x+"_"+y+"_value");
					var image = document.getElementById("img_"+x+"_"+y);
					
					hdnFieldTarget.value='0';
					hdnFieldValue.value='0';
					image.src=imgPath;
				}
			}
		}
	}
	
</script>
{/literal}

<div id="selectItem">
	<table border="0" cellpadding="1" cellspacing="0" style="border:1px solid #666666;">
    {section name=y loop=$typematrix}
        <tr>
        {section name=x loop=$typematrix[y]}
            <td>
				<img src="{$typematrix[y][x].img}" 
					alt="{$typematrix[y][x].type}" 
					id="selected_{$typematrix[y][x].type}"
					border="0"
					style="border:2pt solid white; cursor:pointer;"
					onclick="selectItem('{$typematrix[y][x].type}', '{$typematrix[y][x].img}');"
					onmousemove="showInfo({$typematrix[y][x].type});"
					onmouseout="hideDiv('divInfo');"/>
            </td>
        {/section}
        </tr>
	{/section}
    </table>
</div>
<p></p>



<div id="divRoomsDropDown" style="display:none; position:absolute; padding:4px; border:1px solid #999999; background-color:#FFFFFF;">
	{$lang.select_a_room}
	<form name="roomlist">
		<select name="roomSelect" id="roomSelect">
			<option value="0">
				-
			</option>
			{section name=x loop=$roomlist}
				<option value={$roomlist[x].roomid}>
					{$roomlist[x].title}
				</option>
			{/section}
		</select>
		<input type="button" value="{$lang.ok}" onclick="validateSelectRoom()">
	</form>
</div>


<div id="divInfo" style="display:none; position:absolute; padding:4px; border:1px solid #999999; background-color:#FFFFFF;">
	&nbsp;
</div>

<form action="" method="post">
	<table border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
		{if $show_coordinates}
			<tr>
				<td style="height:13px; width:13px;" bgcolor="#ffffff">
					<div id="x_axis_{$smarty.section.i.index}"  style="
						height:13px; width:13px; max-width:13px; max-height:13px; font-size:10px; align:center; vertical-align:center; overflow:hidden;
						">
					&nbsp;
					</div>
				</td>
				{section name=i loop=$columnindex}
					<td style="height:13px; width:13px; font-size:0.8em; align:center; vertical-align:center;" bgcolor="#ffffff" align="center">
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
				<td style="height:13px; width:13px; font-size:0.8em; align:center; vertical-align:center;" bgcolor="#ffffff" align="center">
					<div id="x_axis_{$smarty.section.i.index}"  style="
						height:13px; width:13px; max-width:13px; max-height:13px; font-size:10px; align:center; vertical-align:center; overflow:hidden;
						">
						{$rowindex[y]}
					</div>
				</td>
			{/if}
		{section name=x loop=$matrix[y]}
			<td bgcolor="#FFFFFF">
				<input type="hidden" value="{$matrix[y][x].type}" name="field_{$matrix[y][x].y}_{$matrix[y][x].x}" id="field_{$matrix[y][x].y}_{$matrix[y][x].x}" />
				<input type="hidden" value="{$matrix[y][x].real_value}" name="field_{$matrix[y][x].y}_{$matrix[y][x].x}_value" id="field_{$matrix[y][x].y}_{$matrix[y][x].x}_value" />
				<img src="{$matrix[y][x].img}" 
					alt="img" 
					border="0" 
					id="img_{$matrix[y][x].y}_{$matrix[y][x].x}" 
					style="cursor:pointer;"
					onclick="paintTile({$matrix[y][x].y},{$matrix[y][x].x});"
				/>
			</td>
		{/section}
		</tr>
	{/section}
	</table>
    <p><input type="submit" name="save" value="{$lang.save}" />&nbsp;<input type="button" name="clear" value="{$lang.delete}" onclick="unsetAll({$room.height},{$room.width},'{$image_white}')" /></p>
</form>