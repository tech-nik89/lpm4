<div class="headline">{$download.name}</div>
<table width="100%" border="0">
	<tr>
		<td valign="top">
			<table width="100%" border="0">
				{if $download.version|trim != ''}
					<tr>
						<td width="150"><strong>{$lang.version}:</strong></td>
						<td>{$download.version}</td>
					</tr>
				{/if}
				{if $download.description|trim != ''}
					<tr>
						<td valign="top"><strong>{$lang.description}:</strong></td>
						<td>{$download.description}</td>
					</tr>
				{/if}
				<tr>
					<td><strong>{$lang.filesize}:</strong></td>
					<td>{$download.size}</td>
				</tr>
				<tr>
					<td><strong>{$lang.uploader}:</strong></td>
					<td>{$download.nickname}</td>
				</tr>
				<tr>
					<td width="20%"><strong>{$lang.up_day}:</strong></td>
					<td>{$download.timestamp|date_format}</td>
				</tr>
			</table>
		</td>
		{if $download.thumbnail|trim != ''}
			<td align="right" valign="top">
				<img src="{$download.thumbnail}" border="0" class="thumbnail" />
			</td>
		{/if}
	</tr>
</table>

{if $download.disabled != '1'}
	<div class="headline">{$lang.download}</div>
	{if $download.allowed == true}
		<form action="" method="post" name="dl">
			<input type="hidden" name="download" value="do_it" />
			<p>
				<input type="submit" name="downloadbutton" onClick="{$download.url} document.dl.submit('download');" value="{$download.downloadthis}" />
			</p>
		</form>
	{else}
		<p>{$lang.dl_login_required}</p>
	{/if}
{/if}

{if $download.release_notes|trim != ""}
	<div class="headline">{$lang.release_notes}</div>
	<div id="release_notes" style="display:none;">
		{$download.release_notes}
	</div>
	<p><a href="javascript:void(0);" onclick="$('#release_notes').toggle(500);">{$lang.show_release_notes}</a></p>
{/if}

{if count($comments) > 0}
	<a name="comments"></a><div class="headline">{$lang.comments}</div>
	{section name=i loop=$comments}
		<div class="comment">
			<p>
				<strong>{$comments[i].nickname}</strong> ({$comments[i].time})<br />
				{$comments[i].text}
			</p>
		</div>
	{/section}
{/if}

{if $loggedin == true}
	<form action="" method="post">
		<div class="headline">{$lang.comment_add}</div>
		<textarea style="width:100%; height:150px;" name="comment"></textarea>
		<p><input type="submit" name="add" value="{$lang.add}" />
	</form>
{/if}