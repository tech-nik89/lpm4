<div class="headline">{$lang.restore}</div>

<form action="" method="post">

	<div style="padding-top:5px;">
		<textarea name="backup" style="width:100%; height:200px;"></textarea>
	</div>
	
	<p>
		<input type="submit" name="doRestore" value="{$lang.restore}" />
	</p>
	
</form>

<div class="headline">{$lang.upload}</div>

<form action="" method="post" enctype="multipart/form-data" name="upload">
	<p><strong>XML-LPM4 Backup Files</strong>(*.xml)</p>
	<p>
		<input type="file" name="file" />
		<input type="submit" name="submit" value="{$lang.upload}" />
	</p>
</form>