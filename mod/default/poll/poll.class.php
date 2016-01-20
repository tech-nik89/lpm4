<?php
class poll
{
	private $varlength;
	private $color;
	private $db;

	function __construct(&$db, $barlength, $color)
	{
		$this->db=$db;
		$this->barlength=$barlength;
		$this->color=$color;
	}

	function countPolls()
	{
		$sql="SELECT * FROM ".MYSQL_TABLE_PREFIX."poll AS P 
			INNER JOIN ".MYSQL_TABLE_PREFIX."poll_questions AS Q ON P.ID=Q.pollID GROUP BY Q.pollID";
		$result=@mysql_num_rows($this->db->query($sql));
		return $result;
	}

	function countActivePolls()
	{
		$sql="SELECT * FROM ".MYSQL_TABLE_PREFIX."poll AS P
			INNER JOIN ".MYSQL_TABLE_PREFIX."poll_questions AS Q ON P.ID=Q.pollID WHERE P.active=1 GROUP BY Q.pollID;";
		$result=@mysql_num_rows($this->db->query($sql));
		return $result;
	}

	function getSumOfAnswers($pollID)
	{
		$sql="SELECT SUM(count) AS allvotes FROM ".MYSQL_TABLE_PREFIX."poll_questions AS Q WHERE Q.pollID='".(int)$pollID."';";
		$result=mysql_fetch_assoc($this->db->query($sql));
		return $result['allvotes'];
	}

	function getActivePolls($userID, $limit=0, $count=10)
	{
		global $comments;
		$sql=$this->db->query("SELECT * FROM ".MYSQL_TABLE_PREFIX."poll WHERE active='1' ORDER BY ID DESC LIMIT ".(int)$limit.",".(int)$count.";");
		$results=array();
		while($result=mysql_fetch_assoc($sql))
		{
			$result['name']=makeHtmlURL(makeLineBreaks($result['name']), makeURL('poll', array('pollid'=>$result['ID'])));
			$result['commenturl']=makeURL('poll', array('pollid'=>$result['ID']))."#comments";
			$result['questions']=$this->getQuestionsByPollID($result['ID']);
			$result['given_answers']=$this->getSumOfAnswers($result['ID']);
			$result['allready_voted']=$this->hasAllreadyVoted($userID, $result['ID']); 
			$result['comment_count']=$comments->count('poll', $result['ID']);
			$results[]=$result;
		}
		return $results;
	}	

	function getPolls($limit, $count, $orderby="date", $orderhow="DESC")
	{
		global $comments;
		$sql=$this->db->query("SELECT * FROM ".MYSQL_TABLE_PREFIX."poll ORDER BY ".secureMySQL($orderby)." ".secureMySQL($orderhow)." LIMIT ".((int)$limit).",".secureMySQL($count).";");
		while($result=mysql_fetch_assoc($sql))
		{
			$result['name']=makeHtmlURL(makeLineBreaks($result['name']), makeURL('poll', array('pollid'=>$result['ID'])));
			$result['commenturl']=makeURL('poll', array('pollid'=>$result['ID']))."#comments";
			$result['questions']=$this->getQuestionsByPollID($result['ID']);
			$result['given_answers']=$this->getSumOfAnswers($result['ID']);
			$result['comment_count']=$comments->count('poll', $result['ID']);
			$result['allready_voted']=1;
			$polls[]=$result;
		}
		return $polls;

	}
	
	function getPollByID($pollid, $userid)
	{
		$sql="SELECT * FROM ".MYSQL_TABLE_PREFIX."poll WHERE ID='".(int)$pollid."'";
		$result=mysql_fetch_assoc($this->db->query($sql));
		$result['name']=makeLineBreaks($result['name']);
		$result['questions']=$this->getQuestionsByPollID($result['ID']);
		$result['given_answers']=$this->getSumOfAnswers($result['ID']);
		$result['allready_voted']=($result['active']==1)?$this->hasAllreadyVoted($userid, $result['ID']):1;
		return $result;
	}
	
	function getRandomPoll()
	{
		$randompoll = $this->db->selectOneRow(MYSQL_TABLE_PREFIX.'poll', "*", "`active`=1", "RAND()");
		$randompoll['name'] = makeLineBreaks($randompoll['name']);
		$randompoll['questions'] = @$this->getQuestionsByPollID($randompoll['ID']);
		return $randompoll;
	}
	
	function getQuestionsByPollID($pollID)
	{
		$sql=("SELECT * FROM ".MYSQL_TABLE_PREFIX."poll_questions WHERE pollID='".(int)$pollID."' ORDER BY ID ASC;");
		$results=$this->db->query($sql);
		$poll=array();
		while($result=mysql_fetch_assoc($results))
		{
			$poll[]=$result;
		}
		return $this->calculatePercentageAndAdColor($poll);
	}

	function calculatePercentageAndAdColor($poll)
	{
		$sum=0;
		$highest=0;
		$result=array();
		if($poll!==null)
		{
			foreach($poll as $question)
			{
				$sum+=$question['count'];
				$highest=($question['count']>$highest)?$question['count']:$highest;
			}
			$counter=0;
			foreach($poll as $question)
			{
				if($sum!=0)
				{
					$question['percentage']=round($question['count']/$sum,4)*100;
					$question['percentage_from_highest']=round($question['count']/$highest,4);    
					$question['bar_length']=$question['percentage_from_highest']*$this->barlength;
				}
				else
				{
					$question['percentage']=0;
					$question['percentage_from_highest']=0;  
					$question['bar_length']=0; 
				}
				if(is_array($this->color))
				{
					if($counter>=count($this->color))
						$counter=0;
					$question['color']=$this->color[$counter++];
				}			
				else
				{
					$question['color']=$this->color;
				}
				$result[]=$question;
			}
		}
		else
		{
			$result=$poll;
		}
		return $result;
	}

	function hasAllreadyVoted($identification, $pollID)
	{
		$all_voters=explode(",",$this->getAllVoters((int)$pollID));
		return (is_array($all_voters))?in_array($identification, $all_voters):false;
	}

	function getAllVoters($pollID)
	{
		$result=@mysql_fetch_assoc($this->db->query("SELECT voted FROM ".MYSQL_TABLE_PREFIX."poll WHERE ID='".(int)$pollID."';"));
		return $result['voted'];
	}

	function vote($identification, $pollID, $QIDs)
	{
		$all_voters=$this->getAllVoters($pollID);
		$all_voters.=$identification.",";
		$sql="UPDATE ".MYSQL_TABLE_PREFIX."poll SET voted='".$all_voters."', votes=votes+1 WHERE ID='".(int)$pollID."';";
		$this->db->query($sql);
		foreach($QIDs as $QID)
		{
			$sql=$this->db->query("UPDATE ".MYSQL_TABLE_PREFIX."poll_questions AS Q SET Q.count=Q.count+1 WHERE Q.pollID='".(int)$pollID."' AND Q.ID='".(int)$QID."';");
		}
		return $sql;
	}

	function addPoll($text, $buttontype)
	{
		$sql="INSERT INTO ".MYSQL_TABLE_PREFIX."poll (name, date, button) VALUES ( '".secureMySQL($text)."','".time()."', '".secureMySQL($buttontype)."');";
		return $this->db->query($sql);
	}	

	function addQuestion($text, $pollID)
	{
		$sql="INSERT INTO ".MYSQL_TABLE_PREFIX."poll_questions (pollID, text) VALUES ('".(int)$pollID."', '".secureMySQL($text)."');";
		return $this->db->query($sql);
	}

	function existsPoll($ID)
	{
		$sql=@mysql_fetch_assoc($this->db->query("SELECT * FROM ".MYSQL_TABLE_PREFIX."poll WHERE ID='".(int)$ID."';"));
		return ($sql!="")?true:false;
	}	

	function existsQuestion($QID)
	{
		$sql=@mysql_fetch_assoc($this->db->query("SELECT * FROM ".MYSQL_TABLE_PREFIX."poll_questions WHERE ID='".(int)$QID."';"));
		return ($sql!="")?true:false;
	}

	function updatePoll($text, $pollID, $button="radio")
	{
		$sql="UPDATE ".MYSQL_TABLE_PREFIX."poll SET name='".secureMySQL($text)."' , button='".secureMySQL($button)."' WHERE ID='".(int)$pollID."';";
		return $this->db->query($sql);
	}

	function updateQuestion($text, $QID)
	{
		$sql="UPDATE ".MYSQL_TABLE_PREFIX."poll_questions SET text='".secureMySQL($text)."' WHERE ID='".(int)$QID."';";
		return $this->db->query($sql);
	}

	function deletePoll($PID)
	{
		$sql="DELETE FROM ".MYSQL_TABLE_PREFIX."poll WHERE ID='".(int)$PID."';";
		return $this->db->query($sql);
	}

	function deleteQuestionByPID($PID)
	{
		$sql="DELETE FROM ".MYSQL_TABLE_PREFIX."poll_questions WHERE pollID='".(int)$PID."';";
		return $this->db->query($sql);
	}
	
	function deleteQuestionByID($ID)
	{
		$sql="DELETE FROM ".MYSQL_TABLE_PREFIX."poll_questions WHERE ID='".(int)$ID."';";
		return $this->db->query($sql);
	}

	function getQuestionByID($ID)
	{
		$sql="SELECT * FROM ".MYSQL_TABLE_PREFIX."poll_questions WHERE ID='".(int)$ID."';";
		return mysql_fetch_assoc($this->db->query($sql));
	}

	function isActive($ID)
	{
		$sql=mysql_fetch_assoc($this->db->query("SELECT active FROM ".MYSQL_TABLE_PREFIX."poll WHERE ID='".(int)$ID."';"));
		return $sql['active'];
	}

	function switchActive($ID)
	{
		$active=$this->isActive($ID);
		$active=($active==1)?0:1;
		$sql="UPDATE ".MYSQL_TABLE_PREFIX."poll SET active='".secureMySQL($active)."' WHERE ID='".(int)$ID."';";
		return $this->db->query($sql);
	}
	
	function resetPoll($ID)
	{
		$this->db->query("UPDATE ".MYSQL_TABLE_PREFIX."poll SET voted = '', votes = 0 WHERE ID='".(int)$ID."';");
		$this->db->query("UPDATE ".MYSQL_TABLE_PREFIX."poll_questions SET `count` = 0 WHERE pollID='".(int)$ID."';");
	}

}	
?>