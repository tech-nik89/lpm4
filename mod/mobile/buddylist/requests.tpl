<div class="headline">{$lang.buddy_requests}</div>


{section name=i loop=$requests}
	<div class="list">
		<div style="float:left;">
			<a href="{$requests[i].url}">{$requests[i].nickname}</a>
		</div>
		<div style="float:right;">
			<form action="" method="post">
				<input type="hidden" name="userid" value="{$requests[i].userid}" />
				<input type="submit" name="accept" value="{$lang.accept}" />
				<input type="submit" name="discard" value="{$lang.discard}" />
			</form>
		</div>
		<div stlye="clear:both;"></div>
	</div>
{/section}