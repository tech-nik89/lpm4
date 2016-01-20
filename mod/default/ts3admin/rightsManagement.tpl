<script type="text/javascript">		
	var deletegroupperm = "{$lang.deletegroupperm}";
	var addgroupperm = "{$lang.addgroupperm}";
	
	if(selectedGroup==undefined) {
		var selectedGroup=null;
		var selectedGroup_className="";
		var selectedGroupName="";
		var selectedGroupID="";
		
		var permlist = new Array();
		var permnamelist = new Array();
	}
	function selectGroup(trelement, sgid, groupname) {
		selectedGroupName=groupname;
		selectedGroupID=sgid;
		
		//Clear Class
		if(selectedGroup!=null) {
			selectedGroup.className=selectedGroup_className;
		}
		
		//Set Class
		selectedGroup_className=trelement.className;
		selectedGroup=trelement;
		selectedGroup.className="selected_row";
		
		//Set Perms
		var thisgroup = null;
		eval("thisgroup=group"+sgid+";");
		
		if(thisgroup!='undefined' && thisgroup!=null){
			for(perm in permlist) {
				perm = permlist[perm];
				var val = "";
				if(permnamelist[perm].startsWith("i_")){
					val = thisgroup[perm]+" ";
				}
				if(thisgroup[perm]!=undefined && thisgroup[perm]!=0) {					
					{if $ts3vserver_rights.r_edit_grouprights==1}
					document.getElementById("permoption"+perm).innerHTML=val+'<a href="ajax_request.php?mod=ts3admin&file=rightsManagement.ajax&sid='+sid+'&vsid='+vsid+'&sgid='+sgid+'&permid='+perm+
						'&askdelete=1" title="'+deletegroupperm+'" id="group'+sgid+'perm'+perm+'" ><img src="'+imgsrc+'/delete.png"></a>';	
					{else}
					document.getElementById("permoption"+perm).innerHTML=val+'<img src="'+imgsrc+'/permission.png">';	
					{/if}
				}else{ 
					{if $ts3vserver_rights.r_edit_grouprights==1}
					document.getElementById("permoption"+perm).innerHTML=val+'<a href="ajax_request.php?mod=ts3admin&file=rightsManagement.ajax&sid='+sid+'&vsid='+vsid+'&sgid='+sgid+'&permid='+perm+
						'&askadd=1" title="'+addgroupperm+'" id="group'+sgid+'perm'+perm+'" ><img src="'+imgsrc+'/add.png"></a>';	
					{else}
					document.getElementById("permoption"+perm).innerHTML=val+'';	
					{/if}
				}
				$("#group"+sgid+"perm"+perm).fancybox();
			}
		}
	}
</script>

{if $ajaxcallback}
	<p>{$rmanage_msg}</p>  
    <center><input type="submit" value="{$lang.ok}" onclick="javascript: $.fancybox.close(); return false;" /></center>
    <script type="text/javascript">
		
		var thisgroup = null;
		eval("thisgroup=group"+{$sgid}+";");
		var val = "{$val} ";
		{if $action=="add"}
			thisgroup[{$permid}]=true;
			document.getElementById("permoption{$permid}").innerHTML={if $isint}val+{/if}'<a href="ajax_request.php?mod=ts3admin&file=rightsManagement.ajax&sid='+sid+'&vsid='+vsid+
				'&sgid={$sgid}&permid={$permid}&askdelete=1" title="'+deletegroupperm+'" id="group{$sgid}perm{$permid}" ><img src="'+imgsrc+'/delete.png"></a>';	
			$("#group{$sgid}perm{$permid}").fancybox();
		{else}
			thisgroup[{$permid}]=false;
			document.getElementById("permoption{$permid}").innerHTML={if $isint}val+{/if}'<a href="ajax_request.php?mod=ts3admin&file=rightsManagement.ajax&sid='+sid+'&vsid='+vsid+
				'&sgid={$sgid}&permid={$permid}&askadd=1" title="'+deletegroupperm+'" id="group{$sgid}perm{$permid}" ><img src="'+imgsrc+'/add.png"></a>';	
			$("#group{$sgid}perm{$permid}").fancybox();
		{/if}
    </script>
{else if $ajaxask}
	<div id="rightactiondiv">
		<script type="text/javascript">
        function doRightAction() {
            var val = document.getElementById("right_val").value;
            document.getElementById("rightactiondiv").innerHTML = loading;
			$("#rightactiondiv").load("ajax_request.php?mod=ts3admin&file=rightsManagement.ajax&sid="+sid+"&vsid="+vsid+"&sgid={$sgid}&permid={$permid}&{$rightaction}=1&val="+val);
        }
        </script>
        <p>{$ask_msg}</p>
        {if $inputint}{$lang.value}: {/if}<input type="{if $inputint}number{else}hidden{/if}" id="right_val" value="1" />
        <table style="width:300px;">
            <tr>
                <td><input type="submit" value="{$lang.yes}" style="width:50px;" onclick="javascript: doRightAction(); return false;"/></td>
                <td width="100%"></td>
                <td><input type="submit" value="{$lang.no}"  style="width:50px;" onclick="javascript: $.fancybox.close(); return false;" /></td>
            </tr>
        </table>
    </div>
{else}
<div class="maxcategory">
    <div class="categoryHead">{$lang.groups}
        <div class="toggleCategoryHoder"><a href="" cat="groups" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="groupstbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td colspan="10">
                {if $ts3vserver_rights.r_add_group==1}
                <a href="ajax_request.php?mod=ts3admin&file=newGroup.ajax&sid={$sid}&vsid={$vsid}" id="newGroupA" title="{$lang.newgrouptitle}" ><img src="{$imgsrc}/add.png" /></a>
                <a href="ajax_request.php?mod=ts3admin&file=copyGroup.ajax&sid={$sid}&vsid={$vsid}" id="copyGroupA" title="{$lang.copygrouptitle}" ><img src="{$imgsrc}/copy.png" /></a>
                {/if}
                {if $ts3vserver_rights.r_remove_group==1}
                <a href="ajax_request.php?mod=ts3admin&file=delGroup.ajax&sid={$sid}&vsid={$vsid}" id="delGroupA" title="{$lang.deletegrouptitle}" >
                    <img src="{$imgsrc}/delete.png" /></a>
                {/if}
            </td>
        </tr>
        <tr>
            <th>{$lang.id}</th>
            <th>{$lang.name}</th>
            <th>{$lang.type}</th>
            <th>{$lang.icon}</th>
            <th>{$lang.savedb}</th>
            <!--
            <th>{$lang.sortid}</th>
            <th>{$lang.namemode}</th>
            -->
            <th><!-- Options --></th>
        </tr>
        {foreach from=$groups item=group}
        <tr class="{cycle values='highlight_row,row'}" style="cursor:pointer;" onclick="javascript:selectGroup(this,'{$group.sgid}','{$group.name}');">
            <td>{$group.sgid}</td>
            <td>{$group.name}
            <script type="text/javascript">
                var group{$group.sgid} = new Array();
                {foreach from=$group.perms item=perm}
                    group{$group.sgid}["{$perm.permid}"]={$perm.permvalue};	
                {/foreach}
            </script>
            </td> 
            <td>{$grouptypes[$group.type]}</td> 
            {if $group.iconid>0}
            <td><img src="{$imgsrc}/groupicons/group_{$group.iconid}.png" /></td> 
            {else}
            <td></td> 
            {/if}
            <td><input type="checkbox" disabled {if $group.savedb==1}checked{/if} /></td> 
            <!--
            <td>{$group.sortid}</td> 
            <td>{$group.namemode}</td>
            -->
            <td>
                {if $ts3vserver_rights.r_rename_group==1}
                <a href="ajax_request.php?mod=ts3admin&file=editGroup.ajax&sid={$sid}&vsid={$vsid}&sgid={$group.sgid}" id="editGroupA{$group.sgid}" title="{$lang.editgrouptitle}" >
                    <img src="{$imgsrc}/edit.png" /></a>
                <script type="text/javascript">
                    $("#editGroupA{$group.sgid}").fancybox();
                </script>
                {/if}
            </td>
        </tr>
        {/foreach}
    </table>
</div>
                
<div class="maxcategory">
    <div class="categoryHead">{$lang.permissions} 
        <div class="toggleCategoryHoder"><a href="" cat="permlist" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="permlisttbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        {foreach from=$permlist item=perm}
        <tr class="{cycle values='highlight_row,row'}">
            <td>
                <div style="position:relative;">
                    <div style="margin-bottom:3px; font-weight:bold;">{$perm.permid}: {$perm.permname}</div> 
                    <div style="margin-bottom:6px; padding-left:10px;">{$perm.permdesc}</div>
                    <div id="permoption{$perm.permid}" style="position:absolute; text-align:right; width:100%; top:0px;">
                        <a href="" title="{$lang.permselectgroup}" onclick="javascript:return false;">
                            <img src="{$imgsrc}/permission.png" />
                        </a>
                    </div>
                    <script type="text/javascript">
                        var i = permlist.length;
                        permlist[i] = "{$perm.permid}";
                        permnamelist["{$perm.permid}"]="{$perm.permname}";
                    </script>
                </div>
            </td>
        </tr>
        {/foreach}
    </table>
</div>
                
<script type="text/javascript">
	<!--{literal}
	$("#newGroupA").fancybox();
	$("#copyGroupA").fancybox();
	$("#delGroupA").fancybox();
	$("#editGroupA").fancybox();
	
	{/literal}-->
</script>
{/if}