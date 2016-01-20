<h1>{$lang.movie}</h1>

{assign var='thumbnail_count' value=4}

<form action="" method="post">
	<table width="100%" border="0">
		<colgroup>
			<col width="150" />
			<col width="*" />
		</colgroup>
		<tr>
			<td>{$lang.title}:</td>
			<td><input type="text" style="width:100%;" name="title" value="{$movie.title}" /></td>
		</tr>
		<tr>
			<td>{$lang.urlid}:</td>
			<td>
				<input type="text" style="width:90%;" name="urlid" id="urlid" value="{$movie.urlid}" onchange="LoadThumbnails();" />
				<a href="mod/default/movies/video-id.png" rel="gallery" title="{$lang.help}" style="float:right;">{$lang.help}</a>
			</td>
		</tr>
		<tr>
			<td valign="top">{$lang.description}:</td>
			<td>
				<textarea name="description" style="width:100%;" cols="45" rows="13">{$movie.description}</textarea>
			</td>
		</tr>
		<tr>
			<td>{$lang.order}:</td>
			<td><input type="text" style="width:45px;" name="order" value="{$movie.order}" /></td>
		</tr>
		<tr>
			<td>{$lang.language}:</td>
			<td>{html_options name=language values=$languages output=$languages selected=$movie.language}</td>
		</tr>
		<tr>
			<td>{$lang.hidden}:</td>
			<td><input type="checkbox" name="hidden" value="1"{if $movie.hidden==1} checked="checked"{/if} /> ({$lang.hidden_descr})</td>
		</tr>
		<tr>
			<td>{$lang.domain}:</td>
			<td>
				<select name="domainid">
					<option value="0">{$lang.all}</option>
					{foreach from=$domains item=domain}
						<option value="{$domain.domainid}"{if $domain.domainid == $movie.domainid} selected="selected"{/if}>{$domain.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
	</table>
	
	<h1>{$lang.thumbnail}</h1>
	<a href="javascript:void(0);" onclick="LoadThumbnails();">{$lang.refresh}</a>
	
	<table width="100%" border="0">
		<colgroup>
			<col width="150" />
			<col width="*" />
		</colgroup>
		
		{section name=j loop=$thumbnail_count}
		<tr>
			<td>
				<input type="radio" name="thumbnail" value="{$smarty.section.j.index}" id="thumbnail_button_{$smarty.section.j.index}"{if $movie.thumbnail==$smarty.section.j.index} checked="checked"{/if} />
			</td>
			<td>
				<label for="thumbnail_button_{$smarty.section.j.index}">
					<img src="" id="thumbnail_{$smarty.section.j.index}" alt="Thumbnail {$smarty.section.j.index + 1}" />
				</label>
			</td>
		</tr>
		{/section}
		
	</table>
	
	<p>
		<input type="submit" name="save" value="{$lang.save}" />
	</p>
</form>

<script type="text/javascript" language="javascript">
	function LoadThumbnails() {
		var movieid = $('#urlid').val();
		
		{section name=k loop=$thumbnail_count}
			LoadThumbnail(movieid, {$smarty.section.k.index});
		{/section}
	}
	
	function LoadThumbnail(movieid, thumbnailid) {
		$('#thumbnail_' + thumbnailid).attr('src', 'http://img.youtube.com/vi/' + movieid + '/' + thumbnailid + '.jpg');
	}
	
	$("a[rel=gallery]").fancybox({
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'titlePosition' 	: 'none',
		'titleFormat'       : ''
	});
	
	LoadThumbnails();
</script>