<script type="text/javascript">
	var toggleArray = new Array();
	
	function toggleChilds(myid) {
		findChilds(myid);
		var toggleto = false;
		for(var i=0; i<toggleArray.length; i++) {
			if(!toggleto) {
				toggleto = (document.getElementById(toggleArray[i]).style.display == 'none')?'':'none';
			}
			document.getElementById(toggleArray[i]).style.display = toggleto;
		}
		toggleArray = new Array();
	}
	
	var allchilds = document.getElementsByTagName('tr');
	
	function findChilds(parentid) {
		for(var i=0; i<allchilds.length; i++) {
			if(allchilds[i].getAttribute('name') == parentid) {
				var id = allchilds[i].getAttribute('id');
				toggleArray.push(id);
				findChilds(id);
			}
		}
	}
		
</script>

<div class="headline">{$poll.title}</div>
{$poll.description}
{$lang.state.{$poll.state}}
	<form action="" method="POST">
		<table style="border-collapse:collapse; width:100%;">
			<colgroup>
				<col width="5%" />
				<col width="10%" />
				<col width="30%" />
				<col width="*" />
			</colgroup>
			<tr>
				<th>
					Bewertung
				</th>		
				<th>
					Prozent
				</th>	
				<th>
					Titel
				</th>
				<th>
					Beschreibung
				</th>			
			</tr>
			{foreach from=$questions item=question}
				<tr style="background-color:{$color.{$question.depth}};" name="{$question.parentid}" id="{$question.questionid}" onClick="toggleChilds({$question.questionid});">
					<td>
						{if $sendAvailable}
							{if $question.childs == 0}
								<select style="border:0px; background-color:{$color.{$question.depth+1}}" name="values[{$question.questionid}]">
									<option value="">&nbsp;</option>
									<option value="1" {if $values.{$question.questionid} == '1'}selected="selected"{/if}>1</option>
									<option value="2" {if $values.{$question.questionid} == '2'}selected="selected"{/if}>2</option>
									<option value="3" {if $values.{$question.questionid} == '3'}selected="selected"{/if}>3</option>
									<option value="4" {if $values.{$question.questionid} == '4'}selected="selected"{/if}>4</option>
									<option value="5" {if $values.{$question.questionid} == '5'}selected="selected"{/if}>5</option>
									<option value="6" {if $values.{$question.questionid} == '6'}selected="selected"{/if}>6</option>
								</select>
							{/if}
						{/if}
						
						{if $resultAvailable}
							{$question.value}
						{/if}
					</td>
					<td>
						{section name=arrows start=0 loop=$question.depth step=1}&nbsp;&nbsp;{/section}
						{$question.percent}
					</td>
					<td>
						{section name=arrows start=0 loop=$question.depth step=1}&nbsp;&nbsp;{/section}
						{$question.title}
					</td>
					<td>
						{section name=arrows start=0 loop=$question.depth step=1}&nbsp;&nbsp;{/section}
						{$question.description}
					</td>
				</tr>
			{/foreach}
			{if $sendAvailable}
				<tr>
					<td colspan="4">
						<input type="hidden" name="send" value="1" />
						<input type="submit" value="{$lang.send}" />
					</td>
				</tr>
			{/if}
			{if $resultAvailable}
				<tr>
					<td>
						<strong>{$result}</strong>
					</td>
					<td colspan="3">
					
					</td>
				</tr>
			{/if}
		</table>
	</form>
</form>