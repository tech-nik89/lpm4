{include file='../mod/default/admin/usermenu.tpl' userid=$smarty.get.userid}
<div class="headline">{$lang.options_delete}</div>
<p><font color="#FF0000">{$lang.delete_confim}</font></p>
<form action="" method="post">
	
    <p>
        <input type="submit" name="yes" value="{$lang.yes}" />
        <input type="button" name="no" value="{$lang.no}" onClick="history.back()" />
    </p>
    
</form>