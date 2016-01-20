<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<title>Cards</title>
		<style type="text/css">
			body, td, tr {
				font-family:"Futura Lt BT", "Verdana", "Arial";
				font-size:15px;
				margin:0 0 0 0;
				width:210mm;
			}
		</style>
	</head>
	
	<body>
		{foreach from=$users item=user}
			<div style="float:left; width:104mm;">
				{include file='../mod/default/report/guestcards/card.tpl' user=$user event=$event}
			</div>
			{cycle values=',<div style="clear:left;"></div>,,<div style="clear:left;"></div>,,
							<div style="clear:left;"></div>,,<div style="clear:left;"></div>,,
							<div style="clear:left;"></div><br style="page-break-after:always;" />'}
		{/foreach}
	</body>
</html>