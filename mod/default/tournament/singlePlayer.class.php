<?php
	
	class singlePlayer implements participant
	{
		
		private $name;
		private $id;
		private $type;
		
		function __construct($user, $type = 0) {
			$this->type = $type;
		
			$this->name = $user['nickname'];
			$this->id = (int)$user['userid'];
		}
		
		function getName(){
			return $this->name;
		}
		
		function getUrl() {
			if ((int)$this->type == 0)
				return makeURL('profile', array('userid' => $this->id));
		}
		
		function getId() {
			return $this->id;
		}
		
		function userCanSubmit($enc) {
			global $login;
			return $enc->getPlayer1id() == $login->currentUserId() || $enc->getPlayer2id() == $login->currentUserId();
		}
		
	}
	
?>