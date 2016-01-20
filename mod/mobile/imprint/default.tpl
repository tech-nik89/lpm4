<div class="headline">{$lang.imprint}</div>
			
		{if $entry neq ""}
		<h3>{$lang.law}</h3>
		<p>	{$entry.owner_name}<br/>
			{$entry.owner_street}<br/>
			{$entry.owner_loc}</p>
			
		{if $entry.owner_tel}<p>{$lang.phone}: {$entry.owner_tel}</p>{/if}
		
		{if $entry.owner_mail}<p>{$lang.email}: {mailto address=$entry.owner_mail encode="hex"}</p>{/if}
		
		{if $entry.court}<p>{$lang.court}: {$entry.court}</p>{/if}
		
		<h3>{$lang.cont}</h3>
		<p>	{$entry.cont_name}<br/>
			{$entry.cont_street}<br/>
			{$entry.cont_loc}</p>
		<div style="border-top:1px solid #CCCCCC; padding-top:5px;">
			{$entry.imprint}
        </div>
		{else}
			{$lang.no_imprint}
		{/if}
        
        <p>&nbsp;</p>
<div style="border-top:1px solid #CCCCCC; padding-top:5px;">
	<div class="headline">Credits</div>
	<div>
		&bull; <a href="http://jquery.com/" target="_blank">JQuery - write less, do more JavaScript Library - by John Resig and the jQuery Team</a><br />
		&bull; <a href="http://www.fancybox.net/" target="_blank">Fancybox - Fancy lightbox alternative</a><br />
		&bull; <a href="http://www.smarty.net/" target="_blank">Smarty - Template Engine - by New Digital Group, Inc.</a><br />
		&bull; <a href="http://detectmobilebrowser.com/" target="_blank">Detect Mobile Browser - Open source mobile phone detection</a><br />
		&bull; <a href="http://ckeditor.com/" target="_blank">CKEditor - Javascript WYSIWYG Editor</a><br />
		&bull; <a href="http://flowplayer.org/" target="_blank">flowplayer</a><br />
		&bull; <a href="http://www.famfamfam.com/" target="_blank">FAM FAM FAM - icon sets Silk &amp; Flags - by Mark James</a>
	</div>
</div>