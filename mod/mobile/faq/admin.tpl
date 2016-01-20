	<div class="headline">{$lang.faq}</div>
		
	<form action="" method="post">
		
		<table width="100%" border="0" cellpadding="5" cellspacing="1">
			
			<tr>
				<th style="width:100px">{$lang.order}</th>
				<th>{$lang.question}</th>
				<th style="width:50%">{$lang.answer}</th>
				<th style="width:100px">{$lang.edit}</th>
				<th style="width:50px">{$lang.delete}</th>
			</tr>
			
			{section name=i loop=$list}
				
				<tr class="{cycle values=',highlight_row'}">
					<td><input type="text" name="order_{$list[i].id}" value="{$list[i].faqorder}" style="width:40px;" /></td>
					<td>{$list[i].question}</td>
					<td>{$list[i].answer}</td>
					<td><a href="{$list[i].edit}">{$lang.edit}</a></td>
					<td><input type="checkbox" name="delete_{$list[i].id}" value="1" /></td>
				</tr>
				
			{/section}
			
		</table>
		<br/>
		<input type="submit" name="save" value="{$lang.refresh}" />
		
	</form>
	
	<div class="headline"></div>
	
	{literal}<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "js/tiny_mce/css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "js/tiny_mce/lists/template_list.js",
		external_link_list_url : "js/tiny_mce/lists/link_list.js",
		external_image_list_url : "js/tiny_mce/lists/image_list.js",
		media_external_list_url : "js/tiny_mce/lists/media_list.js",
		});
	</script>{/literal}
	
	

	<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<form action="" method="post">

		<tr>
			<td>{$lang.order}</td>
			<td><input type="text" name="new_order" value="{$edit.faqorder}" style="width:40px;" /></td>
		</tr><tr>
			<td style="width:100px">{$lang.question}</td>
			<td><input type="text" name="new_question" value="{$edit.question}" style="width:533px" /></td>
		</tr><tr>
			<td>{$lang.answer}</td>
			<td><textarea name="new_answer" cols="100" />{$edit.answer}</textarea></td>
		</tr>
			<input type="hidden" name="sid" value="{$edit.id}" />
			<td><input type="submit" name="submit" value="{$lang.save}" /></td>
		</tr>
	</form>
	
	</table>