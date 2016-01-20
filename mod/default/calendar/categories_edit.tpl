<script type="text/javascript" src="js/farbtastic/farbtastic.js"></script>
<link rel="stylesheet" href="js/farbtastic/farbtastic.css" type="text/css" />


<form action="" method="POST">
<input type="hidden" name="categoryId" value="{$categoryId}"/>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td> Titel:</td>
		<td colspan="3">
			<input type="text" style="width:100%" name="title" id="title" value="{$category.title}"></input>
		</td>
	</tr>
	<tr>
		<td> Hintergundfarbe:</td>
		<td> <input type="text" id="backgroundcolor" size="7" maxlength="7" name="backgroundcolor" value="{$category.backgroundcolor}" onChange="backgroundcolorChanged()"></td>
		<td> Textfarbe:</td>
		<td> <input type="text" id="fontcolor" size="7" maxlength="7" name="fontcolor" value="{$category.fontcolor}" onChange="fontcolorChanged()" /></td>
	</tr>
	<tr>
		<td colspan="2"> 
			<div id="backgroundcolor_picker">
			</div> 
		</td>
		<td colspan="2"> 
			<div id="fontcolor_picker">
			</div> 
		</td>
	</tr>
	<tr>
		<td style="vertical-align:top"> Beschreibung:</td>
		<td colspan="3">
			<textarea name="description" style="width:100%" >{$category.description}</textarea>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="submit" name="save" value="{$lang.save}" />
		</td>
		<td colspan="2" style="text-align:right;">
			<input type="submit" onMouseDown="return confirm('{$lang.reallydelete}');" name="delete" value="{$lang.delete}" />
		</td>
	</tr>
</table>



</form>

 <script type="text/javascript">
	function backgroundcolorChanged() {
		var color = $('#backgroundcolor').val();
		$('#backgroundcolor').val(color);
		$('#title').css('background-color', color);
		$.farbtastic('#backgroundcolor_picker').setColor(color);
	}
	
	function fontcolorChanged() {
		var color = $('#fontcolor').val();
		$('#fontcolor').val(color);
		$('#title').css('color', color);
		$.farbtastic('#fontcolor_picker').setColor(color);
	}

	$('#backgroundcolor_picker').farbtastic(function(color) {
		$('#backgroundcolor').val(color);
		$('#title').css('background-color', color);
	});
	
	$('#fontcolor_picker').farbtastic(function(color) {
		$('#fontcolor').val(color);
		$('#title').css('color', color);
	});

	backgroundcolorChanged();
	fontcolorChanged();
 </script>