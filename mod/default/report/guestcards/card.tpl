<div style="background-image:url(mod/default/report/guestcards/background.jpg); 
	height:223px; width:365px; background-repeat:no-repeat;">
	
	<div style="position:relative; top:28mm;" align="center">
		{$user.prename} {if $user.nickname != ''}"{$user.nickname}"{/if} {$user.lastname}
	</div>
	
	<div style="position:relative; top:33mm;" align="center">
		{if $user.userid != ''}ID: {$user.userid|string_format:"%04d"}{/if}
	</div>
	
</div>