<script type="text/javascript">
	function setParent(id) {
		$("#new_parentid").val(id);
	}
</script>
<div class="headline">{$lang.detailedpoll}</div>
<form action="" method="POST">
	<table style="border-collapse:collapse; width:100%;" border="0">
		<colgroup>
			<col width="10%" />
			<col width="*" />
		</colgroup>
		<tr>
			<td>
				Titel
			</td>
			<td>
				<input type="text" name="poll[title]" style="width:100%;" value="{$poll.title}" />
			</td>
		</tr>
		<tr>
			<td style="vertical-align:top;">
				Beschreibung
			</td>
			<td>
				<textarea name="poll[description]" style="width:100%; height:60px;">{$poll.description}</textarea>
			</td>
		</tr>
	</table>
	<br />
	<table style="border-collapse:collapse; width:100%;" border="1">
		<colgroup>
			<col width="5%" />
			<col width="1%" />
			<col width="5%" />
			<col width="5%" />
			<col width="5%" />
			<col width="30%" />
			<col width="*" />
			<col width="5%" />
		</colgroup>
		<tr>
			<th>
				Löschen
			</th>
			<th>
				&nbsp;
			</th>
			<th>
				ID
			</th>
			<th>
				Rang
			</th>
			<th>
				Vater
			</th>
			<th>
				Titel
			</th>
			<th>
				Beschreibung
			</th>
			<th>
				Prozent
			</th>
		</tr>
		{if !empty($questions)}
			{foreach from=$questions item=question}
				{include file=$singlequestion_path
					data=$question
					questionid=$question.questionid}
			{/foreach}
		{/if}
		<tr>
			{include file=$singlequestion_path
				data=''
				questionid=new}
		</tr>
		<tr>
			<td colspan="8">
				<input type="hidden" name="send" value="1" />
				<input type="submit" value="{$lang.save}" />
			</td>
		</tr>
	</table>
</form>