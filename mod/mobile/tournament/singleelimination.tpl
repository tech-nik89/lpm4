<div class="headline">{$lang.tournament_table}</div>
<div style="overflow:auto; width=100%; background-color:#FFFFFF;">
	<table style="border-collapse:collapse;" border="0">
		<tr>
			{section name=rounds loop=$roundsandmaps}
				<td colspan="2">
					<strong>{$lang.round} {$roundsandmaps[rounds].roundNr} </strong>
					{if $roundsandmaps[rounds].map != ''}
						<br /> {$roundsandmaps[rounds].map}
					{/if}
					{if $roundsandmaps[rounds].startTime != ''}
						<br /> {$roundsandmaps[rounds].startTime} {$lang.o_clock}
					{/if}
				</td>
			{/section}
		</tr>
		{section name=rows loop=$field}
			<tr style="height:60px;">
			{section name=rounds loop=$field[rows]}
				{if $field[rows][rounds]}
					{if $field[rows][rounds].round neq 0}
						<td rowspan="{$field[rows][rounds].rowspan}" width="4px" style="padding:0px;">
							<img src="./mod/default/tournament/images/right_down.png" border="0" alt="\"><br />
							<img src="./mod/default/tournament/images/right.png" border="0" width="4px" height="{math equation='x*(pow(2,(y-1))-1)' x=60 y=$field[rows][rounds].round}" alt="|"><br />
							<img src="./mod/default/tournament/images/right_up.png" border="0" alt="/">
						</td>
					{/if}
					<td rowspan="{$field[rows][rounds].rowspan}" colspan="{$field[rows][rounds].colspan}" style="padding:0px">
						{if $field[rows][rounds].round neq 0}
							{include file=$encTempl
									round=$field[rows][rounds].round
									row=$field[rows][rounds].encNr
									encounterWidth=$encounterWidth
									player1name=$field[rows][rounds].p1name
									player1url=$field[rows][rounds].p1url
									player1points=$field[rows][rounds].p1points
									player2name=$field[rows][rounds].p2name
									player2url=$field[rows][rounds].p2url
									player2points=$field[rows][rounds].p2points
									winner=$field[rows][rounds].winner
									link=$field[rows][rounds].link
									colspan=1
									arrow=1
									color=$field[rows][rounds].timestatus.color
									mode=$field[rows][rounds].timestatus.mode}
						{else}
							{include file=$encTempl
									round=$field[rows][rounds].round
									row=$field[rows][rounds].encNr
									encounterWidth=$encounterWidth
									player1name=$field[rows][rounds].p1name
									player1url=$field[rows][rounds].p1url
									player1points=$field[rows][rounds].p1points
									player2name=$field[rows][rounds].p2name
									player2url=$field[rows][rounds].p2url
									player2points=$field[rows][rounds].p2points
									winner=$field[rows][rounds].winner
									link=$field[rows][rounds].link
									colspan=1
									color=$field[rows][rounds].timestatus.color
									mode=$field[rows][rounds].timestatus.mode}
						{/if}
					</td>
				{/if}
			{/section}
			</tr>
		{/section}
		{if $thirdplayoff} 
			<tr>
				<td colspan="{$thirdplayoff.tdnbsp}">
					&nbsp;
				</td>
				<td>
					{include file=$encTempl
						round=$thirdplayoff.round
						row=$thirdplayoff.encNr
						encounterWidth=$encounterWidth
						player1name=$thirdplayoff.p1name
						player1url=$thirdplayoff.p1url
						player1points=$thirdplayoff.p1points
						player2name=$thirdplayoff.p2name
						player2url=$thirdplayoff.p2url
						player2points=$thirdplayoff.p2points
						winner=$thirdplayoff.winner
						link=$thirdplayoff.link
						colspan=1
						color=$thirdplayoff.timestatus.color
						mode=$thirdplayoff.timestatus.mode}
				</td>
			</tr>
		{/if}
	</table>
</div>