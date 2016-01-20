<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<title>Report</title>
		<style type="text/css">
			body, td, tr {
				font-family:Verdana;
				font-size:11px;
				width:210mm;
			}
			th {
				font-weight:bold;
				text-align:left;
				/* border-bottom:1px solid #CCC; */
			}
			.headline {
				font-size:1.3em;
				font-weight:bold;
				border-bottom:1px solid #CCC;
				padding:4px;
				margin-bottom:4px;
			}
		</style>
	</head>
	
	<body>
		<div class="headline">{$mode}</div>
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<th>{$lang.lastname}</th>
				<th>{$lang.prename}</th>
				<th>{$lang.nickname}</th>
			</tr>
			{foreach from=$users item=user}
				<tr>
					<td>{$user.lastname}</td>
					<td>{$user.prename}</td>
					<td>{$user.nickname}</td>
				</tr>
			{/foreach}
		</table>
	</body>
</html>