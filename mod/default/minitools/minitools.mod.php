<?php

	require_once($mod_dir . "/minitools.function.php");

	$lang->addModSpecificLocalization($mod);
	
	/*
	
	$breadcrumbs->addElement($lang->get('stat'), makeURL($mod));
	$statistic['config']['visitors_month_enabled'] = (int)$config->get('stat', 'show-visitors-this-month');

	*/
	$breadcrumbs->addElement($lang->get('minitools'), makeURL($mod));

		
		
	$mode = @$_GET['mode'];
	

	
	
	$availableModes = array('base64', 'roman');

	
	switch($mode) {
		case 'edit':
			$breadcrumbs->addElement($lang->get('minitoolsedit'), makeURL($mod, array('mode' => 'edit')));
			
			if(isset($_POST['send'])) {
				saveMinitools(@$_POST['data']);
				redirect(makeUrl('minitools'));
			}
			
			$smarty->assign('minitools', getAllMinitools($availableModes));
			
			$smarty->assign('path', $template_dir . "/edit.tpl");
			break;
	
		case 'base64':
			$breadcrumbs->addElement($lang->get('base64'), makeURL($mod, array('mode' => 'base64')));
			
			$menu->addSubElement('minitools', $lang->get('base64encode'), 'base64', array('action' => 'encode'));
			$menu->addSubElement('minitools', $lang->get('base64decode'), 'base64', array('action' => 'decode'));
			
			$action = @$_GET['action'];
			if($action=='decode') {
				$breadcrumbs->addElement($lang->get('base64decode'), makeURL($mod, array('mode' => 'base64', 'action' => 'decode')));
				if(isset($_POST['send'])) {
					if(isset($_POST['text']) && trim($_POST['text']) != '') {
						$base64decoded = base64_decode(trim($_POST['text']));
						$smarty->assign('base64decoded', $base64decoded);	
					}
				}
				
				$smarty->assign('path', $template_dir . "/base64decode.tpl");
			} else {
				$breadcrumbs->addElement($lang->get('base64encode'), makeURL($mod, array('mode' => 'base64', 'action' => 'encode')));
			
				if(isset($_POST['send'])) {
					$wrapcount = (int) @$_POST['wrapcount'];
					$wrapcount = ($wrapcount<0)?0:$wrapcount;
					if(isset($_FILES['file']) && $_FILES['file']['name'] != '') {
							$imgfile = $_FILES['file']['tmp_name'];
							$handle = fopen($imgfile, "r");
							$imgbinary = fread(fopen($imgfile, "r"), filesize($imgfile));
							
							$base64encoded = base64_encode($imgbinary);
							
							if($wrapcount != 0) {
								$base64encoded = wrapText($base64encoded, $wrapcount);
							}
							
							if(preg_match("/image\/(jpg|jpeg|png|gif|bmp)/", $_FILES['file']['type'], $hits)) {
								$smarty->assign('presrcdata', 'data:image/'.$hits[1].';base64,');
							}
							$smarty->assign('base64encoded', $base64encoded);						
					} elseif(isset($_POST['text'])) {
						$base64encoded = base64_encode($_POST['text']);
						if($wrapcount != 0) {
								$base64encoded = wrapText($base64encoded, $wrapcount);
							}
						$smarty->assign('base64encoded', $base64encoded);	
					}
				}
				$smarty->assign('path', $template_dir . "/base64encode.tpl");
			}
			break;	
	
		case 'roman':
			$breadcrumbs->addElement($lang->get('roman'), makeURL($mod, array('mode' => 'roman')));
			
			if(isset($_POST['send'])) {
				$decnumber = (int) $_POST['decnumber'];
				$romannumber = $_POST['romannumber'];
				if($decnumber > 0) {
					$smarty->assign('romannumber', getRomanFromDec($decnumber));
					$smarty->assign('decnumber', $decnumber);
				} elseif($romannumber) {
					$smarty->assign('romannumber', $romannumber);
					$smarty->assign('decnumber', getDecFromRoman($romannumber));
				}
			}
		
			$smarty->assign('path', $template_dir . "/romannumbers.tpl");
			break;
	
		default:
			$menu->addSubElement('minitools', $lang->get('edit'), 'edit');
			makeMenueEntries();
			
			$smarty->assign('minitools', getAllVisibleMinitools());
			$smarty->assign('path', $template_dir . "/minitools.tpl");
			break;
	}	
	
?>