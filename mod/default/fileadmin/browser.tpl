<html>
	<head>
		<title>{$lang.fileadmin}</title>
		<link rel="stylesheet" href="mod/default/fileadmin/browser.css" type="text/css" />
		<script src="js/jquery-1.6.1.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			
			var mode = "{$mode}";
			function getUrlParam(paramName)	{
			  var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
			  var match = window.location.search.match(reParam) ;
			 
			  return (match && match.length > 1) ? match[1] : '' ;
			}
			
			function selectFile(fileUrl) {
				if (mode == "") {
					var funcNum = getUrlParam('CKEditorFuncNum');
					window.opener.CKEDITOR.tools.callFunction(funcNum, fileUrl);
				}
				if (mode == "id") {
					var field = window.opener.document.getElementById("{$id}");
					field.value = fileUrl;
				}
				if (mode == "media") {
					var field = window.opener.document.getElementById("{$id}");
					field.value = fileUrl;
				}
				window.close();
			}
			
			function changeTab(id) {
				$(".tab").hide();
				$("#"+id).show();
			}
		</script>
	</head>
	<body>
		<div align="center">
			<a href="javascript:void(0);" onclick="changeTab('fileadmin');">{$lang.fileadmin}</a> 
			{if $mode == '' || $mode == 'id'}
				| <a href="javascript:void(0);" onclick="changeTab('content');">{$lang.content}</a> 
				| <a href="javascript:void(0);" onclick="changeTab('mods');">{$lang.mods}</a> 
				| <a href="javascript:void(0);" onclick="changeTab('forms');">{$lang.forms}</a> 
			{/if}
		</div>
		
		<div class="tab" id="fileadmin">
			<div class="headline">{$lang.fileadmin}</div>
			{foreach from=$tree item=element}
				{include file='../mod/default/fileadmin/tree_element.tpl' element=$element}
			{/foreach}
		</div>
		
		{if $mode == '' || $mode == 'id'}
			<div class="tab" style="display:none;" id="content">
				<div class="headline">{$lang.content}</div>
				<table width="100%" border="0" cellspacing="1" cellpadding="5">
					<tr>
						<th>{$lang.content}</th>
						<th>{$lang.key}</th>
					</tr>
					{foreach from=$pages item=page}
						<tr{cycle values=', class="highlight_row"'}>
							<td>
								<a href="#" onclick="javascript:selectFile('{$page.url}');">{$page.title}</a> 
							</td>
							<td>
								{$page.k}
							</td>
						</tr>
					{/foreach}
				</table>
			</div>
			
			<div class="tab" style="display:none;" id="mods">
				<div class="headline">{$lang.mods}</div>
				<ul>
					{foreach from=$mods item=mod}
						<li>
							<a href="#" onclick="javascript:selectFile('{$mod.url}');">{$mod.mod}</a>
						</li>
					{/foreach}
				</ul>
			</div>
			
			<div class="tab" style="display:none;" id="forms">
				<div class="headline">{$lang.forms}</div>
				<ul>
					{foreach from=$forms item=form}
						<li>
							<a href="#" onclick="javascript:selectFile('{$form.url}');">{$form.title}</a>
						</li>
					{/foreach}
				</ul>
			</div>
		{/if}
	</body>
</html>