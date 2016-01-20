<div class="headline">{$lang.encounter}</div>

<table width="50%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<td width="45%" align="center">
			<strong>
				<a href="{$player1.url}">{$player1.name}</a>
			</strong>
		</td>
		<td  width="10%" align="center">
			<strong>vs.</strong>
		</td>
		<td width="45%" align="center">
			<strong>
				<a href="{$player2.url}">{$player2.name}</a>
			</strong>
		</td>
	</tr>
	
	<tr>
		<td align="right"><strong>{$enc.points1}</strong></td>
		<td align="center"><strong>:</strong></td>
		<td><strong>{$enc.points2}</strong></td>
	</tr>
	
</table>
<br />
<table>
	{if $enc.map != ''}
		<tr>
			<td>{$lang.map}:</td>
			<td>{$enc.map}</td>
		</tr>
	{/if}
	{if $enc.start != ''}
		<tr>
			<td>{$lang.start}:</td>
			<td>{$enc.start}</td>
		</tr>
	{/if}
	{if $enc.end != ''}
		<tr>
			<td>{$lang.end}:</td>
			<td>{$enc.end}</td>
		</tr>
	{/if}
</table>

{if $enc.undoLink != ''}
	{$enc.undoLink}
{/if}

{if $user_can_submit && !$enc.finished}
	{include file='../mod/default/tournament/submiteliminationencounter.tpl'}
{/if}

{if $logged_in}

	<div class="headline">{$lang.communication}</div>

	<script type="text/javascript">
		function sendText() {
			
			$.post("ajax_request.php?mod=tournament&file=communication.ajax",
					{ text: $("#inpChat").val(), tournamentid: {$tournament.tournamentid},
					  encid: {$_GET.encid}, roundid: {$_GET.roundid} },
				function(data) {
					$("#divChat").html(data);
				});
			$("#inpChat").val("");
		}
		
		function refreshText() {
			$.post("ajax_request.php?mod=tournament&file=communication.ajax",
				{ text: '', tournamentid: {$tournament.tournamentid},
				  encid: {$_GET.encid}, roundid: {$_GET.roundid} },
				function(data) {
					$("#divChat").html(data);
				});
		}
		
		window.setInterval('refreshText()', 4000);
		refreshText();
		
	</script>

	{if $user_can_submit}
		<p>
			<input type="text" name="inpChat" value="" id="inpChat" style="width:300px;" 
				maxlength="500" />
			<input type="button" name="btnGo" value="{$lang.go}" onclick="sendText();" />
		</p>
	{/if}

	<div id="divChat"></div>

{/if}