<?php
	
	/**
	 * Project: Higher For Hire
	 * File: avatar.core.php
	 *
	**/
	
	// Include image class
	include('./core/simple.image.core.php');
	
	Class Avatar
	{
		// some pointer to some classes
		private $user;
		private $lang;
		private $login;
		
		// the constructor
		// is not very important, it just sets the pointer
		function __construct(&$user, &$lang, &$login)
		{
			$this->user = $user;
			$this->lang = $lang;
			$this->login = $login;
		}
		
		// upload function
		// uploads a new avatar if $_POST['submit'] is set, otherwise it displays a form
		function upload($userid, $max_byte_size = 2097152)
		{
			global $debug;
			global $config;
			
			$dir = 'media/avatar/';
			$msg = '';
			
			// upload button has been pressed
			if (@$_POST['submit'] == 'Upload') {
				// set allowed file types
				$allowed_types = "(jpg|jpeg|gif|bmp|png)";

				// is really a file?
				if(is_uploaded_file($_FILES["file"]["tmp_name"]))  {
					 // valid extension?
					if(preg_match("/\." . $allowed_types . "$/i", $_FILES["file"]["name"])) {
						// file size okay?
						if($_FILES["file"]["size"] <= $max_byte_size) {
							// width and height okay?
							$size = getimagesize($_FILES['file']['tmp_name']);
							$debug->add('img-size', 'height:' . $size[0] . ' width:' . $size[1]);
							
							// get user
							$u = $this->user->getUserByID($userid);
							
							$filename = uniqid($u['userid'] . "_") . "_" . $_FILES["file"]["name"];
							
							// everything all right, now copy
							if (!file_exists($dir . $filename)) {
								if(copy($_FILES["file"]["tmp_name"], $dir . $filename)) {
									// Resize image if too large
									$image = new SimpleImage();
									$image->load($dir.$filename);
									
									if ($image->getWidth() > (int)$config->get('core', 'img-width')) {
										$image->resizeToWidth((int)$config->get('core', 'img-width'));
									}
									
									if ($image->getHeight() > (int)$config->get('core', 'img-height')) {
										$image->resizeToHeight((int)$config->get('core', 'img-height'));
									}
									
									// Save image
									$this->remove($filename);
									$image->save($dir.$filename);
									
									// upload successfull
									$msg = $this->lang->get('upload_successfull');
									
									// remove old avatar
									$this->remove($u['avatar']);
									
									// update avatar
									$this->user->setAvatar($userid, $filename);
									
								} else
									$msg = $this->lang->get('upload_failed');
							} else
								$msg = $this->lang->get('upload_failed');
						} else
							$msg = $this->lang->get('upload_too_large');
					} else
						$msg = $this->lang->get('upload_bad_extension');
				} else
					$msg = $this->lang->get('upload_failed');
			}
			
			// display the upload-form
			return '
				<p>
					' . $msg . '
				</p>
				
				<form action="" method="post" enctype="multipart/form-data" name="upload">
					
					<input type="file" name="file" />
					<input type="submit" name="submit" value="Upload" />
					
				</form> 
				
				';
			
		}
		
		// removes an avatar from the filesystem
		private function remove($avatar)
		{
			if ($avatar != 'nopic.png')
				@unlink('media/avatar/' . $avatar);
		} 
		
		function makeAvatar($avatar)
		{
            global $config;
            
            if ($config->get('core', 'disable-reflections') == '1') {
                $r = '';
            }
            else {
                $r = ' class="reflect rheight40 ropacity43"';
            }
            
			// if the user has no avatar, return nopic.png
			if ($avatar == '') {
				$a = 'nopic.png';
			}
			else {
				// if the avatar-file of the user doesn't exist, also return nopic.png
				if (file_exists('media/avatar/' . $avatar))
					$a = $avatar;
				else
					$a = 'nopic.png';
			}	
			return '<img src="media/avatar/' . $a . '"' . $r . ' alt="Avatar" />';
		}
		
		// returns the html code containing the avatar
		function get($userid)
		{
			// get user
			$u = $this->user->getUserByID($userid);
			return $this->makeAvatar($u['avatar']);
			
		}
		
	}

?>