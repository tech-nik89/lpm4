<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
	error_reporting(E_ERROR);
?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1" />

	<title>LPM IV - Higher For Hire - Setup</title>
	<link href="install/install.css" rel="stylesheet" type="text/css" />
	<script src="js/jquery-1.6.1.min.js" type="text/javascript"></script>
</head>
	
	<body>
		
        <div align="center">
        
			<?php
                $step = isset($_POST['step']) ? (int)$_POST['step'] : 0;
                
                if ($step == 0)
                    $step = 1;
                
                if (isset($_POST['back']))
                    $step--;
                    
                if (isset($_POST['next']))
                    $step++;
                
                $steps[1] = 'Welcome';
                $steps[2] = 'Requirements';
                $steps[3] = 'Database';
                $steps[4] = 'Admin User';
                $steps[5] = 'Menu';
                $steps[6] = 'Language';
                $steps[7] = 'Summary';
                $steps[8] = 'Setup';
                $steps[9] = 'User import';
                $steps[10] = 'Finished';
                
            ?>
            
            <div style="width:1000px; border:1px solid #696969;">
            
                <div style="height:22px; background-color:#c1d72e; line-height:18px;">
                    &nbsp;
                </div>
                
                <div>
                    <img src="install/header.gif" border="0" alt="header" />
                </div>
                
                <div style="height:22px; background-color:#D5DF3D; line-height:18px; padding-left:225px;">
                    &nbsp;
                </div>
            
            
            
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    
                    <tr>
                        <td align="left" valign="top" width="240px" bgcolor="#F1F1F1">
                            
                            <div style="padding:10px;">
                                
                                <div class="headline">Setup steps</div>
                                
                                <?php
                                    
                                    foreach ($steps as $i => $s)
                                    {
                                        if ($step == $i)
                                            echo '<strong>' . $i . '. ' . $s . '</strong><br />';
                                        else
                                            echo $i . '. ' . $s . '<br />';
                                    }
                                    
                                ?>
                                
                            </div>
                            
                        </td>
                        <td align="left" valign="top">
                            
                            <div style="padding:10px;">
                            
                                <?php
                                    
                                    include('./install/step_'.$step.'.php');
                                    
                                ?>
                            
                            </div>
                    
                        </td>
                    </tr>
                    
                </table>
            
            </div>
		
        </div>
        
	</body>

</html>