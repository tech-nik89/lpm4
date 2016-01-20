<div align="center">
	{php}
		global $db;
		
		$path = './media/boximages/ad/';
		
		$ad = $db->selectOneRow(MYSQL_TABLE_PREFIX."ad", '*', 1, "RAND()");
		
		$smarty->assign('img', $path.$ad['img']);
		$smarty->assign('url', $ad['url']);
	{/php}
	{if $url!=''}
		<a href="{$url}" target="_blank"><img src="{$img}" border="0" alt="ad" width="100%"/></a>
	{/if}
</div>