<style type="text/css">
	.movie {
		background-color:transparent;
		height:180px;
		width:180px;
		border:1px solid #FFF;
	}
	
	.movie:hover {
		background-color:#EAEAEA;
		border:1px solid #CCC;
	}
</style>

<h1>{$lang.overview}</h1>

{if count($movies) > 0}
	<table width="100%" border="0">
		<colgroup>
			{section name=c loop=$columns}
				<col width="{((1 / $columns) * 100)|number_format:2}%" />
			{/section}
		</colgroup>
		<tr>
			{section name=i loop=$movies}
				{if $smarty.section.i.index > 0 && $smarty.section.i.index mod $columns == 0}
					</tr>
					<tr>
				{/if}
				
				<td valign="top" align="center">
					<div class="movie">
						<p>
							<a href="{makeurl mod='movies' movieid=$movies[i].movieid}">
								<strong>{$movies[i].title}</strong>
							</a>
						</p>
						<p>
							<a href="{makeurl mod='movies' movieid=$movies[i].movieid}" style="position:relative;">
								<img src="http://img.youtube.com/vi/{$movies[i].urlid}/{$movies[i].thumbnail}.jpg" width="120" height="90" border="0" style="border:1px solid #DDD;" />
							</a>
						</p>
					</div>
				</td>
			{/section}
		</tr>
	</table>
{/if}