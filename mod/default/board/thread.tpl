<p>{$pages}</p>

{section name=i loop=$pl}
	<a name="post{$pl[i].postid}"></a>
	<div style="padding:5px;">
	
		<div style="padding:5px; border-bottom:1px solid #DDDDDD">
			
			<div style="float:left; width:30%;"><strong>{$pl[i].author}</strong></div>
			<div style="float:left;">{$pl[i].time}</div>
			<div style="float:right;" align="right">
				#{$pl[i].number}
			</div>
			<div style="clear:left; clear:right;"></div>
			
		</div>
		
		{if $isallowed == true || $pl[i].userid == $me.userid || $loggedin == true}
			<div align="right" style="padding-top:2px;">
				<a href="{$pl[i].quote_url}">{$lang.quote}</a>
				{if $isallowed == true || $pl[i].userid == $me.userid}
					| <a href="{$pl[i].edit_url}">{$lang.edit}</a>
					{if $isallowed == true}
						| <a href="{$pl[i].remove_url}">{$lang.remove}</a>
					{/if}
				{/if}
			</div>
		{/if}
		
		<div style="padding:5px; margin-top:5px">
		
			<div style="float:left; width:30%;">
            	{$pl[i].avatar}
				{if $hide_number_of_posts != '1'}
					<p>
						<span style="padding-left:8px;">{$lang.posts}: {$pl[i].posts}</span>
					</p>
				{/if}
				{if $pl[i].usergroups|@Count > 0}
					<p>
						<span style="padding-left:8px;">
							{$lang.member_of}:
							{section name=j loop=$pl[i].usergroups}
								{$pl[i].usergroups[j].name}{if $smarty.section.j.index < $pl[i].usergroups|@Count-1},{/if}
							{/section}
						</span>
					</p>
				{/if}
            </div>
			<div style="float:left; width:60%;">
            	
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
{/section}

{if $showadd == true}
	
    {literal}
    <script language="javascript" type="text/javascript">
		function toggleBB() {
			if (!$("#divBBCodes").is(':visible')) {
				$("#divBBCodes").load('templates/default/bbcode.html');
				$("#divBBCodes").show('fast');
			}
			else {
				$("#divBBCodes").hide('fast');
			}
		}
	</script>
    {/literal}
    
    <a name="addPost"></a>
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
			
				<div style="float:left; width:30%;">{$me.avatar}</div>
				<div style="float:left; width:60%;"><textarea style="width:100%; height:150px;" name="post">{$quote}</textarea></div>
				<div style="clear:left;"></div>
			
			</div>
			
		</div>

        <input type="text" id="attachments" name="attachments" value="" style="display:none;" />
		<div style="padding-left:31%;">
            <input type="submit" name="add" value="{$lang.add}" />
		</div>
		
		<p align="right">
			<a href="javascript:toggleBB();">BB Codes</a>
		</p>
		<div id="divBBCodes" style="display:none;"></div>
		
	</form>
{/if}

<p>{$pages}</p>