<div class="headline">{$movie.name}</div>

<script src="js/flowplayer/flowplayer-3.2.6.min.js"></script>

<a 
	href="{$core.self_url}/media/movie/{$movie.file}" 
	style="display:block;width:100%; height:500px;" 
	id="flowplayerplayer">
</a>

<script language="JavaScript">
	flowplayer("flowplayerplayer", "js/flowplayer/flowplayer-3.2.7.swf", {
		clip: {
			autoPlay: false,
			autoBuffering: false,
			scaling: "fit"
		}
	});
</script>

<p>{$movie.description}</p>

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