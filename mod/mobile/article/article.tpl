<div class="headline">{$article.title}</div>
<p><strong>{$article.author.nickname}</strong> ({$article.timestamp|date_format}): {$article.preview}</p>
<p>{$article.text}</p>
<p>&nbsp;</p>
<a name="comments"></a>
{if count($comments) > 0}
<div class="headline">{$lang.comments}</div>
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