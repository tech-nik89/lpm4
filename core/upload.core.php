<?php

	class Upload {
		
		public $dir = 'media/download/';
		public $max_byte_size = 10485760;
		public $allowed_types = "(jpg|jpeg|gif|bmp|png)";
		public $tag_name = 'file';
		public $file_name = '';
		
		function getForm() {
			return '
				<form action="" method="post" enctype="multipart/form-data" name="upload">
						
					<p>
						<input type="file" name="'.$this->tag_name.'">
					</p>
					<p>
						<input type="submit" name="submit" value="Upload">
					</p>
					
				</form>
				';
		}
		
		function uploadFile() {
			// is really a file?
			if(is_uploaded_file($_FILES[$this->tag_name]["tmp_name"])) {
				 // valid extension?
				if(preg_match("/\." . $this->allowed_types . "$/i", $_FILES[$this->tag_name]["name"])
					&& substr($_FILES[$this->tag_name]["name"], strlen($_FILES[$this->tag_name]["name"]) - 4) != '.php') {
					// file size okay?
					if($_FILES[$this->tag_name]["size"] <= $this->max_byte_size) {
						// $filename = uniqid(time()) . "_" . $_FILES["file"]["name"];
						$filename = $_FILES[$this->tag_name]["name"];
						$this->file_name = $filename;
						
						// everything all right, now copy
						if (!file_exists($this->dir . $filename)) {
							if(copy($_FILES[$this->tag_name]["tmp_name"], $this->dir . $filename))
							{
								$msg = 0; // upload successfull
							} else
								$msg = 1; // Could not copy file. Upload failed.
						} else
							$msg = 5; // File already exists
					} else
						$msg = 2; // This file is too big. Upload failed.
				} else
					$msg = 3; // The exentsion you used is not allowed. Upload failed.
			} else
				$msg = 4; // Upload failed.
			
			return $msg;
		}
		
		function uploadArray() {
			
			$cnt = count($_FILES['file']['name']);
			$msg = '';
			
			for ($j = 0; $j < ($cnt); $j++) {
				// is really a file?
				if(is_uploaded_file($_FILES[$this->tag_name]["tmp_name"][$j])) {
					 // valid extension?
					if(preg_match("/\." . $this->allowed_types . "$/i", $_FILES[$this->tag_name]["name"][$j]) && substr($_FILES[$this->tag_name]["name"][$j], strlen($_FILES[$this->tag_name]["name"][$j]) - 4) != '.php') {
						// file size okay?
						if($_FILES[$this->tag_name]["size"][$j] <= $this->max_byte_size) {
							// $filename = uniqid(time()) . "_" . $_FILES["file"]["name"];
							$filename = $_FILES[$this->tag_name]["name"][$j];
							
							// everything all right, now copy
							if (!file_exists($this->dir . $filename)) {
								if(copy($_FILES[$this->tag_name]["tmp_name"][$j], $this->dir . $filename)) {
									$msg .= 0; // upload successfull
								} else
									$msg .= 1; // Could not copy file. Upload failed.
							} else
								$msg .= 5; // File already exists
						} else
							$msg .= 2; // This file is too big. Upload failed.
					} else
						$msg .= 3; // The exentsion you used is not allowed. Upload failed.
				} else
					$msg .= 4; // Upload failed.
			}
			return $msg;
		}
	}

?>