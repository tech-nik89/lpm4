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
			<tr style="height:95px;">
			{section name=rounds loop=$rounds}
				{if $field[rows][rounds]}
					{if $field[rows][rounds].round neq 1}
						<td rowspan="{$field[rows][rounds].rowspan}" width="4px" style="padding:0px;">
							{if !$field[rows][rounds].nolink}
								{if $field[rows][rounds].fromlink}
									<div style="height: 16px; overflow: hidden;">
									</div>
									<img src="./mod/default/tournament/images/right_down.png" border="0" alt="\" /><br />
									<img src="./mod/default/tournament/images/right.png" border="0" width="4px" height="4px" alt="|" /><br />
									<img src="./mod/default/tournament/images/right_up.png" border="0" alt="/" />
									<div style="height: 56px; overflow: hidden;">
									</div>
								{else}
									<img src="./mod/default/tournament/images/right_down.png" border="0" alt="\" /><br />
									<img src="./mod/default/tournament/images/right.png" border="0" width="4px" height="{math equation='x*(y/2-(2/3))' x=95 y=$field[rows][rounds].rowspan}" alt="|" /><br />
									<img src="./mod/default/tournament/images/right_up.png" border="0" alt="/" />
								{/if}
							{/if}
						</td>
					{/if}
					<td rowspan="{$field[rows][rounds].rowspan}" colspan="{$field[rows][rounds].colspan}" style="padding:0px">
						{if $field[rows][rounds].haslink == 1}
							{include file=$linkTempl
									linktext=$field[rows][rounds].linktext}
						{elseif $field[rows][rounds].haslink == 2}
							<div style="height:140px; overflow: hidden">
								{include file=$linkTempl
									linktext=$field[rows][rounds].linktext}
								<div style="height: 20px; overflow: hidden">
								</div>
								{if $field[rows][rounds].round neq 1}
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
					    		    		colspan=$field[rows][rounds].colspan
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
										colspan=$field[rows][rounds].colspan
										arrow=0
										color=$field[rows][rounds].timestatus.color
										mode=$field[rows][rounds].timestatus.mode}
					    		{/if}
							</div>
						{else}
							{if $field[rows][rounds].round neq 1}
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
										colspan=$field[rows][rounds].colspan
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
										colspan=$field[rows][rounds].colspan
										arrow=0
										color=$field[rows][rounds].timestatus.color
										mode=$field[rows][rounds].timestatus.mode}
							{/if}
						{/if}
					</td>
				{/if}
			{/section}
			</tr>
		{/section}
	</table>
</div>