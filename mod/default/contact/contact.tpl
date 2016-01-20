<div class="headline">{$contact}</div>

<p>{$contact_description}</p>
<p>&nbsp;</p>

<form action="{$url}" method="post">
	<p>{$lang.subject}: <input type="text" name="subject" id="subject" style="width:300px;" value="{$subject}" /></p>
	<p><textarea name="text" id="text" style="width:100%; height:150px;">{$text}</textarea></p>
	<p><input type="submit" name="send" value="{$lang.send}" {if $subject != "" && $text != ""}disabled="disabled"{/if} /></p>
	<div style="display:none;"><input type="text" name="email" value="" /></div>
</form>