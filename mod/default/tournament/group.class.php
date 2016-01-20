<?php
	
	class group implements participant
	{
		
		private $name;
		private $id;
		private $type;
		private $tournamentid;
		private $founderid;
		
		function __construct($group, $type = 0, $tournamentid = 0)
		{
			$this->type = $type;
			$this->name = $group['name'];
			$this->id = (int)$group['groupid'];
			$this->founderid = (int)$group['founderid'];
			$this->tournamentid = $tournamentid;
		}
		
		function getName()
		{
			return $this->name;
		}
		
		function getUrl()
		{
			if ((int)$this->type == 0)
				return makeURL('tournament', array('mode' => 'viewgroup', 'tournamentid' => $this->tournamentid, 'groupid' => $this->id));
		}
		
		function getId()
		{
			return $this->id;
		}
		
		function userCanSubmit($enc)
		{
			global $login, $config;
			
			if ($config->get('tournament', 'group-only-leader-can-submit') == 0) {
				$members = $this->getMembers();
				foreach ( $members as $member ) {
					if ( $member['userid'] == $login->currentUserId() )
						return true;
				}
			}
			else {
				if ( $this->founderid == $login->currentUserId() ) {
					return true;
				}
			}
			return false;
		}
		
		function getMembers()
		{
			global $db;
			
			$tbl_gr = MYSQL_TABLE_PREFIX . 'tournamentgroupregister';
			$tbl_u = MYSQL_TABLE_PREFIX . 'users';
			
			$members = $db->selectList($tbl_gr . '`, `' . $tbl_u, "*",
				"`tournamentid`=".$this->tournamentid." AND `groupid`=".$this->id."
				AND `".$tbl_gr."`.`memberid`=`".$tbl_u."`.`userid`");
			
			foreach ($members as $i => $member) {
				$members[$i]['url'] = makeURL('profile', array('userid' => $member['userid']));
			}
			
			return $members;
		}
		
	}
	
?>