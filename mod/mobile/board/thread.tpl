<link rel="stylesheet" href="thickbox/thickbox.css" type="text/css" media="screen" />

<p>{$pages}</p>

{section name=i loop=$pl}
	<a name="post{$pl[i].postid}"></a>
	<div style="padding:5px;background-color:#FFFFFF;">
	
		<div style="padding:5px; border-bottom:1px solid #DDDDDD">
			
			<div style="float:left; width:30%;"><strong>{$pl[i].author}</strong></div>
			<div style="float:left;">{$pl[i].time}</div>
			<div style="float:right;" align="right">
				#{$pl[i].number}
			</div>
			<div style="clear:left; clear:right;"></div>
			
		</div>
		
		<div style="padding:5px; margin-top:5px;">
		
			<div style="float:left; width:100%;">
            	
                <div>
                
	            	{$pl[i].post}
            	
                </div>
                
                <div style="padding-top:20px;">
                    {section name=j loop=$pl[i].attachments}
                        <a href="media/attachments/{$pl[i].attachments[j]}" class="thickbox" rel="post{$pl[i].postid}">
                            <img src="mod/default/media/thumbs.php?width=100&file=../../../media/attachments/{$pl[i].attachments[j]}" 
                            border="1" />
                        </a>
                    {/section}
                </div>
            
            </div>
			<div style="clear:left;"></div>
		
		</div>
		
	</div>
		
		
		
		{if $isallowed == true || $pl[i].userid == $me.userid || $loggedin == true}
			<div align="right" style="background-color:#FFFFFF;">
				<a href="{$pl[i].quote_url}">{$lang.quote}</a>
				{if $isallowed == true || $pl[i].userid == $me.userid}
					| <a href="{$pl[i].edit_url}">{$lang.edit}</a>
					{if $isallowed == true}
						| <a href="{$pl[i].remove_url}">{$lang.remove}</a>
					{/if}
				{/if}
			</div>
			<p>&nbsp;</p>
		{/if}
		
	{/section}

{if $showadd == true}
	
    {literal}
    <script language="javascript" type="text/javascript">
		window.setInterval('updateAttachments()', 1000);
		var attachments = 0;
		
		function updateAttachments()
		{
			var hdnField = document.getElementById('attachments');
			var list = hdnField.value.split(',');
			var info = document.getElementById('attachments_info');
			
			attachments = 0;
			
			for (i = 0; i < list.length; i++)
			{
				if (list[i] != '')
					attachments++;
			}
			
			info.innerHTML = attachments + " {/literal}{$lang.files_attached}{literal}";
		}
	</script>
    {/literal}
    
	<form action="" method="post">
	
		<input type="hidden" name="unique_id" value="{$unique_id}" />
		
		<div style="padding:5px;">
				
			<div style="padding:5px; border-bottom:1px solid #DDDDDD">
				
				<div style="float:left; width:30%;"><strong>{$me.nickname}</strong></div>
				<div style="float:left;">{$lang.now}</div>
				<div style="float:right;" align="right">#{$number}</div>
				<div style="clear:left; clear:right;"></div>
				
			</div>
			
			<div style="padding:5px; margin-top:5px;">
			
				<div style="width:98%;"><textarea style="width:100%; height:150px;" name="post">{$quote}</textarea></div>

			
			</div>
			
		</div>
		
        <input type="text" id="attachments" name="attachments" value="" style="display:none;" />
		
		<div style="padding-left:10px;">
            <input type="submit" name="add" value="{$lang.add}" />
            {$attach_file_button}
            <div id="attachments_info" align="left" style="padding-top:8px;">
            	0 {$lang.files_attached}
	        </div>
		</div>
		
	</form>
{/if}

<p style="padding-left:10px;">{$pages}</p>
