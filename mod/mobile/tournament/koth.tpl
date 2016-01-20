<div class="headline">{$lang.tournament_table}</div>

<table border="0" cellpadding="0" cellspacing="1">
	<tr>
		{section name=rounds loop=$roundsandmaps}
			<td width="{$encounterWidth}px" style="padding-left:5px; border: 1pt solid #888888;">
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
	{section name=rows loop=$table}
		<tr style="height:65px;">
			{section name=rounds loop=$table[rows]}
				<td style="border: 1pt solid {$table[rows][rounds].timestatus.color};">
					{include file=$encTempl
						round=$table[rows][rounds].round
						row=$table[rows][rounds].encNr
						player1name=$table[rows][rounds].p1name
						player1url=$table[rows][rounds].p1url
						player1points=$table[rows][rounds].p1points
						player2name=$table[rows][rounds].p2name
						player2url=$table[rows][rounds].p2url
						player2points=$table[rows][rounds].p2points
						winner=$table[rows][rounds].winner
						link=$table[rows][rounds].link}
				</td>
			{/section}
		</tr>
	{/section}
</table>

