<?php

// include language file
$lang->addModSpecificLocalization($mod);

// add breadcrumbs
$breadcrumbs->addElement($lang->get('mod_name'), makeURL($mod));

$tbl_ad = MYSQL_TABLE_PREFIX . 'ad';

if(isset($_POST['send']) && $_POST['send']==1) {
	if(isset($_POST['ads'])) {
		foreach($_POST['ads'] as $id=>$ad) {
			if(isset($ad['delete']) && $ad['delete']=="on") {
				$element = $db->selectOne($tbl_ad, 'img', "`adid`=".(int)$id);
				unlink('./media/boximages/ad/'.$element);
				$db->delete($tbl_ad, "adid=".$id);
			}
			else {
				if(substr($ad['url'], 0, 7)!="http://")
					$ad['url']="http://".$ad['url'];
				$db->update($tbl_ad, "url='".$ad['url']."'", "adid=".$id); 
			}
		}
	}
	if($_FILES['image']['size'] > 0) {
		$upload = new Upload();
		$upload->dir = 'media/boximages/ad/';
		$upload->tag_name = 'image';
		$upload->uploadFile();
		
		$imgdir="./media/boximages/ad/";
		include_once './core/simple.image.core.php';
		$image = new SimpleImage();
		$image->load($imgdir.$upload->file_name);
		if($image->getWidth() > (int) $config->get('ad', 'standard_image_width'))
			$image->resizeToWidth((int) $config->get('ad', 'standard_image_width'));
		if($image->getHeight() > (int) $config->get('ad', 'standard_image_height'))
			$image->resizeToHeight((int) $config->get('ad', 'standard_image_height'));
		
		unlink($imgdir.$upload->file_name);
		$image->save($imgdir.$upload->file_name);
		
		if(substr($_POST['newurl'], 0, 7)!="http://")
			$_POST['newurl']="http://".$_POST['newurl'];
		$db->insert($tbl_ad, array('img', 'url'), array("'".$upload->file_name."'", "'".$_POST['newurl']."'"));
	}
}

$allads = $db->selectList($tbl_ad, '*', 1);
$counter=0;
$ads = array();
foreach($allads as $ad)
{
	$ads[$counter]=$ad;
	$ads[$counter++]['imgurl']=makeUrl('ad', array('mode'=>'edit'));
}

$smarty->assign('ads', $ads);

$smarty->assign('path', $template_dir."/ad.view.tpl");
?>