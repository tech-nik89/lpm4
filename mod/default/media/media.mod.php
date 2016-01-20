<?php
	
	/**
	* Higher For Hire
	* Media Portal
	*/
	
	require_once($mod_dir . "/media.function.php");
	
	$download_dir = 'media/download/';
	
	$lang->addModSpecificLocalization($mod);
	
	$isallowed = $rights->isallowed($mod, 'manage');
	$smarty->assign('isallowed', $isallowed);
	$mode = $_GET['mode'];
	$categoryid = isset($_GET['categoryid']) ? (int)$_GET['categoryid'] : 0;
	$downloadid = isset($_GET['downloadid']) ? (int)$_GET['downloadid'] : 0;
	$movieid = isset($_GET['movieid']) ? (int)$_GET['movieid'] : 0;
	
	$smarty->assign('thumbnailwidth', $config->get($mod, 'thumbnailwidth'));
	
	$breadcrumbs->addElement($lang->get('media'), makeURL($mod));
	
	$category = getCategory($categoryid);
	if ($category['name'] == '')
		$category['name'] = $lang->get('media');
	
	if ($this->isInstalled('fileadmin')) {
		$smarty->assign('fileadmin_installed', true);
	}
	
	$parents = getParentList($categoryid);
	foreach ($parents as $parent)
		$breadcrumbs->addElement($parent['name'], makeURL($mod, array('categoryid' => $parent['categoryid'])));
	
	if ($rights->isAllowed($mod, 'upload') && $categoryid > 0 && $downloadid == 0 && $movieid == 0) {
		$menu->addSubElement($mod, $lang->get('adddownload'), 'adddownload', array('categoryid' => $categoryid));
		$menu->addSubElement($mod, $lang->get('addpictures'), 'addpictures', array('categoryid' => $categoryid));
	}
	
	if ($isallowed) {
		if ($downloadid == 0 && $movieid == 0) {
			$menu->addSubElement($mod, $lang->get('createcategory'), 'createcategory', array('categoryid' => $categoryid));
			$menu->addSubElement($mod, $lang->get('download_stat'), 'statistic');
			
			if ($categoryid > 0) {
				$menu->addSubElement($mod, $lang->get('editcategory'), 'editcategory', array('categoryid' => $categoryid));
				$menu->addSubElement($mod, $lang->get('removecategory'), 'removecategory', array('categoryid' => $categoryid));
				$menu->addSubElement($mod, $lang->get('editpictures'), 'editpictures', array('categoryid' => $categoryid));
				$menu->addSubElement($mod, $lang->get('addmovie'), 'addmovie', array('categoryid' => $categoryid));
			}
		}
		
		if ($downloadid > 0) {
			$menu->addSubElement($mod, $lang->get('editdownload'), 'editdownload', array('categoryid' => $categoryid, 'downloadid' => $downloadid));
			$menu->addSubElement($mod, $lang->get('removedownload'), 'removedownload', array('categoryid' => $categoryid, 'downloadid' => $downloadid));
		}
		
		if ($movieid > 0) {
			$menu->addSubElement($mod, $lang->get('editmovie'), 'editmovie', array('categoryid' => $categoryid, 'movieid' => $movieid));
			$menu->addSubElement($mod, $lang->get('removemovie'), 'removemovie', array('categoryid' => $categoryid, 'movieid' => $movieid));
		}
	}
	
	if ($categoryid == 0) {
		$menu->addSubElement($mod, $lang->get('top_downloads'), 'top-downloads');
		$menu->addSubElement($mod, $lang->get('new_downloads'), 'new-downloads');
	}
	
	switch ($mode)
	{	
		case 'statistic':
			if ($rights->isAllowed($mod, 'manage')) {
			
				$smarty->assign('first_download_year', getFirstDownloadCounterYear());
				
				@$selected_month = (int)$_POST['Date_Month'];
				@$selected_year = (int)$_POST['Date_Year'];
				if ($selected_month == 0) $selected_month = date("n");
				if ($selected_year == 0) $selected_year = date("Y");
				$selected_month_ts = mktime(0, 0, 0, $selected_month, 15, $selected_year);
				$smarty->assign('selected_month_ts', $selected_month_ts);
				
				$breadcrumbs->addElement($lang->get('download_stat'), makeURL($mod, array('mode' => 'statistic')));
				$smarty->assign('path', $template_dir.'/download-stat.tpl');
				$stat['today'] = getDownloadCounter(
					mktime(0, 0, 0, date("n"), date("j"), date("Y")),
					mktime(23, 59, 59, date("n"), date("j"), date("Y"))
				);
				$stat['this_month'] = getDownloadCounter(
					mktime(0, 0, 0, date("n"), 1, date("Y")),
					mktime(23, 59, 59, date("n"), date("t"), date("Y"))
				);
				$stat['this_year'] = getDownloadCounter(
					mktime(0, 0, 0, 1, 1, date("Y")),
					mktime(23, 59, 59, 12, 31, date("Y"))
				);
				$stat['this_week'] = getDownloadCounter(
					mktime(0, 0, 0, date("n"), date("j"), date("Y")) - 604800, // 604800 equals 1 week in seconds
					mktime(23, 59, 59, date("n"), date("j"), date("Y"))
				);
				
				$month = $selected_month;
				$year = $selected_year;
				$stat['month'] = date("M", mktime(0, 0, 0, $month, 1, $year));
				$stat['year'] = $year;
				$stat['month_value'] = $month;
				$stat['month_last_day'] = date("t", mktime(0, 0, 0, $month, 1, $year));
				$max = 0;
				for ($day = 1; $day <= date("t", $selected_month_ts); $day++) {
					$counter = getDownloadCounter(
						mktime(0, 0, 0, $month, $day, $year),
						mktime(23, 59, 59, $month, $day, $year)
					);
					if ($counter > $max) $max = $counter;
					$stat['days'][] = array(
						'day' => $day,
						'counter' => $counter
					);
				}
				$stat['max'] = $max;
				
				$bots = $config->get('media', 'show-bots') == '1' ? '' : "WHERE `browseragent` <> 'Bot'";
				
				$result = $db->query("SELECT dl.downloadid, dlc.timestamp, dl.categoryid, dl.name 
										FROM ".MYSQL_TABLE_PREFIX."media_downloads_counter AS dlc 
										LEFT JOIN ".MYSQL_TABLE_PREFIX."media_downloads AS dl 
										ON dlc.downloadid = dl.downloadid 
										".$bots."
										ORDER BY dlc.timestamp DESC LIMIT 10;");
				while ($row = mysql_fetch_assoc($result)) {
					$stat['last'][] = $row;
				}
				
				$stat['export_url'] = 'ajax_request.php?mod=media&amp;file=stat.export&amp;month='.$month.'&amp;year='.$year;
				$smarty->assign('dl_stat', $stat);
			}
			break;
		case 'top-downloads':
			$breadcrumbs->addElement($lang->get('top_downloads'), makeURL($mod, array('mode' => 'top-downloads')));
			$smarty->assign('path', $template_dir."/top-downloads.tpl");
			
			$top = getTopDownloads();
			$smarty->assign('top', $top);
			
			break;
		
		case 'new-downloads':
			$breadcrumbs->addElement($lang->get('new_downloads'), makeURL($mod, array('mode' => 'new-downloads')));
			$smarty->assign('path', $template_dir."/newest-downloads.tpl");
			
			$new = getNewestDownloads();
			$smarty->assign('new', $new);
			
			break;
		
		case 'removemovie':
		
			if ($isallowed && $mode == 'removemovie') {
				if (isset($_POST['yes']))
				{
					removeMovie($movieid);
				} else {
					$mv = getMovie($movieid);
					$breadcrumbs->addElement($mv['name'], makeURL($mod, array('categoryid' => $categoryid, 'movieid' => $movieid)));
					$breadcrumbs->addElement($lang->get('removemovie'), makeURL($mod, array('categoryid' => $categoryid, 'movieid' => $movieid, 'mode' => 'removemovie')));
					$smarty->assign('path', $template_dir . "/removemovie.tpl");
					$smarty->assign('movie', $mv);
					$smarty->assign('url_no', makeURL($mod, array('categoryid' => $categoryid, 'movieid' => $movieid)));
					break;
				}
			}
		
		case 'editmovie':
		
			if ($isallowed && $mode == 'editmovie') {
				if (isset($_POST['save'])) {
					editMovie($movieid, $_POST['name'], $_POST['file'], $_POST['description']);
					$notify->add($lang->get('media'), $lang->get('editmovie_done'));
				} else {
					$mv = getMovie($movieid);
					$breadcrumbs->addElement($mv['name'], makeURL($mod, array('categoryid' => $categoryid, 'movieid' => $movieid)));
					$breadcrumbs->addElement($lang->get('editmovie'), makeURL($mod, array('categoryid' => $categoryid, 'movieid' => $movieid, 'mode' => 'editmovie')));
					$smarty->assign('path', $template_dir . "/editmovie.tpl");
					$smarty->assign('movie', $mv);
					break;
				}
			}
		
		case 'addmovie':
		
			if ($isallowed && $mode == 'addmovie') {
				if (isset($_POST['add'])) {
					addMovie($categoryid, $_POST['name'], $_POST['file'], $_POST['description']);
				} else {
					$breadcrumbs->addElement($lang->get('addmovie'), makeURL($mod, array('categoryid' => $categoryid, 'mode' => 'addmovie')));
					$smarty->assign('files', listAvailableMovies());
					$smarty->assign('path', $template_dir . "/addmovie.tpl");
					break;
				}
			}
		
		case 'editpictures':
		
			if ($isallowed && $mode == 'editpictures') {
				$breadcrumbs->addElement($lang->get('editpictures'), makeURL('media', array('categoryid' => $categoryid, 'mode' => 'editpictures')));
				
				$smarty->assign('path', $template_dir."/removepictures.tpl");
				$folder = $category['uniqid'];
				$smarty->assign('folder', $folder);
				@$imglist = scandir('media/images/'.$folder.'/');
				if ($imglist !== false) {
					foreach ($imglist as $i => $img) {
						if ($img == '.' || $img == '..') {
							unset($imglist[$i]);
						}
						else {
							if (isset($_POST['submit']) && @$_POST[str_replace(".", "_", $img)] == '1') {
								unlink('media/images/'.$folder.'/'.$img);
								unset($imglist[$i]);
							}
						}
					}
					$smarty->assign('images', $imglist);
				}
				break;
			}
		
		case 'addpictures':
		
			if ($rights->isAllowed($mod, 'upload') && $mode == 'addpictures') {
				$forms = array();
				$max_uploads = (int)$config->get('media', 'number-of-uploads');
				if ($max_uploads == 0)
					$max_uploads = 10;
					
				for ($i = 1; $i <= $max_uploads; $i++)
					$forms[] = $i;
				$smarty->assign('forms', $forms);
				
				if (isset($_POST['add'])) {
					addPictures($categoryid);
					$notify->add($lang->get('media'), $lang->get('addpicture_done'));
				}
				$smarty->assign('folders', listImageFolders());
				$smarty->assign('path', $template_dir . "/addpictures.tpl");
				$breadcrumbs->addElement($lang->get('addpictures'), makeURL($mod, array('categoryid' => $categoryid, 'mode' => 'addpictures')));				
				break;
			}
		
		case 'removedownload':
		
			if ($isallowed && $mode == 'removedownload') {
				if (isset($_POST['yes'])) {
					removeDownload($downloadid);
					$notify->add($lang->get('media'), $lang->get('removedownload_done'));
					redirect(makeURL($mod, array('categoryid' => $categoryid)));
					break;
				} else {
					$smarty->assign('path', $template_dir . "/removedownload.tpl");
					$smarty->assign('url_no', makeURL($mod, array('categoryid' => $categoryid, 'downloadid' => $downloadid)));
					break;
				}
			}
		
		case 'editdownload':
		
			if ($isallowed && $mode == 'editdownload'){
				if (isset($_POST['save'])){
					@editDownload($downloadid, $_POST['categoryid'], $_POST['name'], $_POST['description'], $_POST['version'], $_POST['path'], $_POST['release_notes'], $_POST['thumbnail'], $_POST['disable']);
				} else {
					$smarty->assign('path', $template_dir . "/editdownload.tpl");
					$dl = getDownload($downloadid);
					
					$breadcrumbs->addElement($dl['name'], makeURL($mod, array('categoryid' => $categoryid, 'downloadid' => $downloadid)));
					$breadcrumbs->addElement($lang->get('editdownload'), makeURL($mod, array('categoryid' => $categoryid, 'downloadid' => $downloadid, 'mode' => 'editdownload')));
					
					$smarty->assign('categories', categoryTree());
					$smarty->assign('download', $dl);
					$smarty->assign('dir', $download_dir);
					
					break;
				}
			}
		
		case 'adddownload':
		
			if ($rights->isAllowed($mod, 'upload') && $mode == 'adddownload') {
				if (isset($_POST['add']) && trim($_POST['name']) != '') {
					if (strpos($_FILES['file']['name'], '&') > 0) {
						$notify->add($lang->get('media'), $lang->get('and_not_allowed'));
					}
					else {
						if (@$_POST['path'] == '') {
							$upload = new Upload();
							$upload->dir = 'media/download/'.$category['uniqid'].'/';
							if (!is_dir($upload->dir)) {
								mkdir($upload->dir, 0777, true);
							}
							@chmod($upload->dir, 0777);
							$upload->tag_name = 'file';
							$upload->allowed_types = '(.*)';
							
							if ($config->get('media', 'max-upload-size') > 0)
								$upload->max_byte_size = $config->get('media', 'max-upload-size');
							else
								$upload->max_byte_size = 10485760;
								
							$msg = $upload->uploadFile();
							if ($msg == 0) {
								@addDownload($categoryid, $_POST['name'], $_POST['description'], $upload->file_name, $_POST['version'], $_POST['release_notes'], $_POST['thumbnail'], $_POST['disable']);
								redirect(makeURL($mod, array('categoryid' => $categoryid)));
							}
							else {
								$notify->add($lang->get('media'), 'Upload Failed. Error Code '.$msg);
							}
						}
						else {
							@addDownload($categoryid, $_POST['name'], $_POST['description'], $_POST['path'], $_POST['version'], $_POST['release_notes'], $_POST['thumbnail'], $_POST['disable']);
							redirect(makeURL($mod, array('categoryid' => $categoryid)));
						}
					}
				}
				else {
					$breadcrumbs->addElement($lang->get('adddownload'), makeURL($mod, array('categoryid' => $categoryid, 'mode' => 'adddownload')));
					$smarty->assign('files', listAvailableDownloads());
					$smarty->assign('path', $template_dir . "/adddownload.tpl");
					$smarty->assign('dir', $download_dir);
				}
				break;
			}
		
		case 'removecategory':
		
			if ($isallowed && $mode == 'removecategory') {
				if (isset($_POST['yes'])) {
					removeCategory($categoryid);
					$categoryid = $category['parentid'];
				} else {
					$breadcrumbs->addElement($lang->get('removecategory'), makeURL($mod, array('mode' => 'removecategory')));
					$smarty->assign('path', $template_dir . "/removecategory.tpl");
					$smarty->assign('url_no', makeURL($mod, array('categoryid' => $categoryid)));
					break;
				}
			}
		
		case 'editcategory':
			if ($isallowed && $mode == 'editcategory') {
				$smarty->assign('languages', $lang->listLanguages());
				$grouplist = $rights->getAllGroups();
				$smarty->assign('groups', $grouplist);
				
				if (isset($_POST['save'])) {
					$assigned_groups = array();
					foreach ($grouplist as $group) {
						if (@$_POST['group_'.$group['groupid']] == '1')
							$assigned_groups[] = $group['groupid'];
					}
					editCategory($categoryid, $_POST['name'], $assigned_groups, $_POST['language']);
				}
				else {
					
					$breadcrumbs->addElement($lang->get('editcategory'), makeURL($mod, array('mode' => 'editcategory', 'categoryid' => $categoryid)));
					$category = getCategory($categoryid);
					$smarty->assign('category', $category);
					
					$assigned_groups = array_row($db->selectList('media_categories_permissions', '*', '`categoryid`='.$categoryid), 'groupid');
					$smarty->assign('permissions', $assigned_groups);
					
					$smarty->assign('path', $template_dir . "/editcategory.tpl");
					break;
				}
			}
		
		case 'createcategory':
			if ($isallowed && $mode == 'createcategory') {
				$smarty->assign('languages', $lang->listLanguages());
				$grouplist = $rights->getAllGroups();
				$smarty->assign('groups', $grouplist);
				
				if (isset($_POST['create']) && trim($_POST['name']) != '') {
					$assigned_groups = array();
					foreach ($grouplist as $group) {
						if (@$_POST['group_'.$group['groupid']] == '1')
							$assigned_groups[] = $group['groupid'];
					}
					createCategory($_POST['name'], $categoryid, $assigned_groups, $_POST['language']);
				} else {
					$breadcrumbs->addElement($lang->get('createcategory'), makeURL($mod, array('mode' => 'createcategory')));
					$smarty->assign('path', $template_dir . "/createcategory.tpl");
					break;
				}
			}
		default:
			
			if (isVisible($categoryid)) {
			
				$showcategories = true;
				
				if ($downloadid > 0) {
					$dl = getDownload($downloadid);
					if (isset($_POST['download'])) {
						increaseDownloadCounter($downloadid);
						$dl['counter']++;
						$addr = trim($config->get('media', 'mail-notification-address'));
						if ($addr != '') {
							$text = '<p><strong><a href="'.getSelfURL().'/'.makeURL($mod, array('categoryid' => $categoryid, 'downloadid' => $downloadid)).'">'.$dl['name'].'</a></strong></p>';
							$text .= '<p>'.$lang->get('timestamp').': '.date('d.m.Y H:i').'</p>';
							$text .= '<p>IP: '.getRemoteAdr().'</p>';
							$me = $login->currentUser();
							if (null != $me) {
								$text .= '<p>UserID: '.$me['userid'].'</p>';
								$text .= '<p>'.$lang->get('nickname').': '.$me['nickname'].'</p>';
								$text .= '<p>'.$lang->get('email').': '.$me['email'].'</p>';
							}
							$eMail->send($lang->get('download_notification'), $text, $addr);
						}
					}
					
					if ($login->currentUser() !== false) {
						$smarty->assign('loggedin', true);
						if (isset($_POST['add']))
							$comments->add($mod, $login->currentUserID(), $_POST['comment'], $downloadid);
					}
					$smarty->assign('comments', $comments->get($mod, $downloadid));
					
					$breadcrumbs->addElement($dl['name'], makeURL($mod, array('categoryid' => $categoryid, 'downloadid' => $downloadid)));
					
					if (isVisible($dl['categoryid'])) {
						if (!isset($_GET['statistic'])) {
							$smarty->assign('hide_author', $config->get('media', 'hide-upload-author'));
							$smarty->assign('hide_date', $config->get('media', 'hide-upload-date'));
							$smarty->assign('path', $template_dir . "/download.tpl");
							
							if ($config->get($mod, 'download-login-required') == '0' || $login->currentUser() !== false)
								$dl['allowed'] = true;
							else
								$dl['allowed'] = false;
								
							$dl['downloadthis'] = str_replace("%n", $dl['name'], $lang->get('downloadthis'));
							$path = 'media/download/'.$category['uniqid'].'/'.$dl['file'];
							if (!file_exists($path))
								$path = $dl['file'];
							if (!file_exists($path)) {
								$notify->add('Error', 'File not found');
							}
							else {
								$h = (int)$config->get($mod, 'download-window-height');
								$w = (int)$config->get($mod, 'download-window-width');
								
								$dl['url'] = "javascript:window.open('".$path."', '_blank', 'width=".$w.", height=".$h.", resizable=yes');";
								$dl['size'] = formatFileSize(filesize($path));
								$days = floor((time() - $dl['timestamp']) / 86400); // 86400 equals 1 day in seconds
								@$dl['dls_per_day'] = number_format($dl['counter'] / $days, 1);
								$dl['description'] = $bbcode->parse($dl['description']);
								$dl['release_notes'] = $bbcode->parse($dl['release_notes']);
								
								$smarty->assign('download', $dl);
							}
						}
						else {
							if ($rights->isAllowed($mod, 'manage')) {
								$breadcrumbs->addElement($lang->get('download_stat'), makeURL($mod, array('categoryid' => $categoryid, 'downloadid' => $downloadid, 'statistic' => '')));
								
								$smarty->assign('first_download_year', getFirstDownloadCounterYear($downloadid));
								
								@$selected_month = (int)$_POST['Date_Month'];
								@$selected_year = (int)$_POST['Date_Year'];
								if ($selected_month == 0) $selected_month = date("n");
								if ($selected_year == 0) $selected_year = date("Y");
								$selected_month_ts = mktime(0, 0, 0, $selected_month, 15, $selected_year);
								$smarty->assign('selected_month_ts', $selected_month_ts);
								
								$smarty->assign('path', $template_dir.'/download-stat.tpl');
								$stat['today'] = getDownloadCounter(
									mktime(0, 0, 0, date("n"), date("j"), date("Y")),
									mktime(23, 59, 59, date("n"), date("j"), date("Y")),
									$downloadid
								);
								$stat['this_month'] = getDownloadCounter(
									mktime(0, 0, 0, date("n"), 1, date("Y")),
									mktime(23, 59, 59, date("n"), date("t"), date("Y")),
									$downloadid
								);
								$stat['this_year'] = getDownloadCounter(
									mktime(0, 0, 0, 1, 1, date("Y")),
									mktime(23, 59, 59, 12, 31, date("Y")),
									$downloadid
								);
								$stat['this_week'] = getDownloadCounter(
									mktime(0, 0, 0, date("n"), date("j"), date("Y")) - 604800, // 604800 equals 1 week in seconds
									mktime(23, 59, 59, date("n"), date("j"), date("Y")),
									$downloadid
								);
								
								$month = $selected_month;
								$year = $selected_year;
								$stat['month'] = date("M", mktime(0, 0, 0, $month, 1, $year));
								$stat['year'] = $year;
								$max = 0;
								for ($day = 1; $day <= date("t", $selected_month_ts); $day++) {
									$counter = getDownloadCounter(
										mktime(0, 0, 0, $month, $day, $year),
										mktime(23, 59, 59, $month, $day, $year),
										$downloadid
									);
									if ($counter > $max) $max = $counter;
									$stat['days'][] = array(
										'day' => $day,
										'counter' => $counter
									);
								}
								$stat['max'] = $max;
								
								$stat['export_url'] = 'ajax_request.php?mod=media&amp;file=stat.export&amp;downloadid='.$downloadid.'&amp;month='.$month.'&amp;year='.$year;	
								$smarty->assign('dl_stat', $stat);
							}
						}
						
						if ($rights->isAllowed($mod, 'manage'))
							$menu->addSubElement($mod, $lang->get('download_stat'), '', array('categoryid' => $categoryid, 'downloadid' => $downloadid, 'statistic' => ''));
					}
					else {
						$notify->add($lang->get('media'), $lang->get('access_denied'));
					}
					
					$showcategories = false;
				}
				
				if ($movieid > 0) {
					$mv = getMovie($movieid);
					
					if (isVisible($mv['categoryid'])) {
						$breadcrumbs->addElement($mv['name'], makeURL($mod, array('categoryid' => $categoryid, 'movieid' => $movieid)));
						
						$smarty->assign('movie', $mv);
						$smarty->assign('path', $template_dir . "/movie.tpl");
						
						if ($login->currentUser() !== false) {
							$smarty->assign('loggedin', true);
							if (isset($_POST['add']))
								$comments->add($mod.'-movie', $login->currentUserID(), $_POST['comment'], $movieid);
						}
						$smarty->assign('comments', $comments->get($mod.'-movie', $movieid));
					}
					else {
						$notify->add($lang->get('media'), $lang->get('access_denied'));
					}
					
					$showcategories = false;
				}
				
				if ($showcategories == true) {
					$list = listCategories($categoryid);
					$smarty->assign('list', $list);
					$smarty->assign('hide_submedia', $config->get('media', 'hide-submedia'));
					
					$smarty->assign('downloads', listDownloads($categoryid));
					$img = listPictures($categoryid);
					$smarty->assign('pictures', $img);
					$smarty->assign('movies', listMovies($categoryid));
					
					@$category['folder'] = $category['uniqid'];
					$smarty->assign('category', $category);
					$smarty->assign('path', $template_dir . "/list.tpl");
				}
			} else {
				$notify->add($lang->get('media'), $lang->get('access_denied'));
			}
	}
	
?>