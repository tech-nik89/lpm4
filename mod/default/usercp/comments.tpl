<div class="headline">{$lang.my_comments}</div>
<script type="text/javascript">
	function removeComment(commentid) {
		$("#comment_"+commentid).html('<img src="mod/default/admin/working.gif" border="0" />');
		$("#comment_"+commentid).load("ajax_request.php?mod=usercp&file=remove_comment.ajax&commentid="+commentid);
	}
</script>
<form action="" method="post">
	<p>
		{$lang.find}:
		<input type="text" name="txtFind" value="{$find}" style="width:300px;" />
		<input type="submit" name="sbtFind" value="{$lang.go}" />
	</p>
</form>
<p>{$pages}</p>
<table width="100%" border="0" cellpadding="5">
	<tr>
		<th>{$lang.mod}</th>
		<th>{$lang.timestamp}</th>
		<th>&nbsp;</th>
	</tr>
	{foreach from=$comment_list item=comment}
		<tr>
			<td><strong>{$comment.mod}</strong></td>
			<td><strong>{$comment.timestamp_str}</strong></td>
			<td>
				<div id="comment_{$comment.commentid}">
					<strong>
						<a href="#" onclick="removeComment({$comment.commentid});">{$lang.remove}</a>
					</strong>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="3">{$comment.text}</td>
		</tr>
	{/foreach}
</table>
<p>{$pages}</p>