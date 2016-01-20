<script language="javascript" type="text/javascript">
	{literal}
	$(document).ready(function() {
		updatePosts();
	});
	window.setInterval('updatePosts()', 8000);
	
	function updatePosts() {
		$("#shoutbox").load("ajax_request.php?mod=shoutbox&file=posts.ajax");
	}
	
	function writePost() {
		$.post("ajax_request.php?mod=shoutbox&file=posts.ajax",
					{ text: $("#text").val() },
					function(data) {
						$("#shoutbox").html(data);
					});
		$("#text").val("");
		hideCounter('text');
	}
	
	function checkStr(textbox, keyCode)
	{
		if (keyCode == 13) { // Enter Pressed
			writePost();
		}
		
		var strlen = textbox.value.length;
		var maxstr = 254;
		var strleft = 0;
		if (strlen >= maxstr ) {
			textbox.value = textbox.value.substring(0,maxstr);
		} else {
			strleft = maxstr - strlen;
		}
		document.getElementById('shoutbox_chars_left').innerHTML=strleft;
	}
	
	function hideCounter(textbox) {
		var strlen = document.getElementById(textbox).value.length;
		if (strlen==0) {
			document.getElementById('shoutbox_chars_left').innerHTML='&nbsp;';
		}
	}
	
	{/literal}
</script>

{if $reverse==1}
	<div id="shoutbox" style="overflow:hidden;">
	&nbsp;
	</div>
{/if}
{if $loggedin}
	<textarea style="width:100%; height:40px;" id="text" rows="4" cols="12" onChange="checkStr(this, -1)" onFocus="checkStr(this, -1)" onBlur="checkStr(this, -1); hideCounter('text');" onKeyDown="checkStr(this, -1)" onKeyUp="checkStr(this, event.keyCode)" ></textarea>
	<div style="margin-top:3px; float:left;" id="shoutbox_chars_left" align="left">
		&nbsp;
	</div>
	<div style="margin-top:3px;" align="right">
		<input type="button" name="post" value="{$lang.go}" onClick="writePost();" />
	</div>
	<div style="float:clear;"></div>
{/if}
{if $reverse==0}
	<div id="shoutbox" style="overflow:hidden;">
	&nbsp;
	</div>
{/if}
<p align="center"><a href="{$shoutbox_url}">{$lang.shoutbox_archive}</a></p>