<div class="headline">Step 2: Requirements</div>
<?php
	$okay = true;
?>
<table border="0" cellspacing="1" cellpadding="5">
	<tr>
		<td width="300">&nbsp;</td>
		<td width="160"><strong>Required</strong></td>
		<td width="160"><strong>Available</strong></td>
	</tr>
	<tr>
		<td><strong>PHP Version</strong></td>
		<td>5.0.0</td>
		<td>
			<?php
				$version = explode(".", phpversion());
				if ($version[0] >= 5)
					echo '<font color="#00AA00">'.phpversion().'</font>';
				else
				{
					$okay = false;
					echo '<font color="#DD0000">'.phpversion().'</font>';
				}
			?>
		</td>
	</tr>
	<tr>
		<td><strong>chmod of directory '/templates_c'<br>
		</strong></td>
		<td>0777</td>
		<td>
			<?php
				$perms = substr(sprintf('%o', fileperms('./templates_c')), -4);
				if ($perms == '0777')
					echo '<font color="#00AA00">'.$perms.'</font>';
				else
				{
					$okay = false;
					echo '<font color="#DD0000">'.$perms.'</font>';
				}
			?>
		</td>
	</tr>
	<tr>
		<td><strong>chmod of directory '/config'</strong></td>
		<td>0777</td>
		<td>
			<?php
				$perms = substr(sprintf('%o', fileperms('./config/')), -4);
				if ($perms == '0777')
					echo '<font color="#00AA00">'.$perms.'</font>';
				else
				{
					$okay = false;
					echo '<font color="#DD0000">'.$perms.'</font>';
				}
			?>
		</td>
	</tr>
	<tr>
		<td><strong>chmod of directory '/media/avatar'<br>
		</strong></td>
		<td>0777</td>
		<td>
			<?php
				$perms = substr(sprintf('%o', fileperms('./media/avatar')), -4);
				if ($perms == '0777')
					echo '<font color="#00AA00">'.$perms.'</font>';
				else
				{
					$okay = false;
					echo '<font color="#DD0000">'.$perms.'</font>';
				}
			?>
		</td>
	</tr>
	<tr>
		<td><strong>chmod of directory '/media/sponsor'<br>
		</strong></td>
		<td>0777</td>
		<td>
			<?php
				$perms = substr(sprintf('%o', fileperms('./media/sponsor')), -4);
				if ($perms == '0777')
					echo '<font color="#00AA00">'.$perms.'</font>';
				else
				{
					$okay = false;
					echo '<font color="#DD0000">'.$perms.'</font>';
				}
			?>
		</td>
	</tr>
    <tr>
		<td><strong>chmod of directory '/media/attachments'<br>
		</strong></td>
		<td>0777</td>
		<td>
			<?php
				$perms = substr(sprintf('%o', fileperms('./media/attachments')), -4);
				if ($perms == '0777')
					echo '<font color="#00AA00">'.$perms.'</font>';
				else
				{
					$okay = false;
					echo '<font color="#DD0000">'.$perms.'</font>';
				}
			?>
		</td>
	</tr>
	<tr>
		<td><strong>chmod of directory '/media/download'<br>
		</strong></td>
		<td>0777</td>
		<td>
			<?php
				$perms = substr(sprintf('%o', fileperms('./media/download')), -4);
				if ($perms == '0777')
					echo '<font color="#00AA00">'.$perms.'</font>';
				else
				{
					$okay = false;
					echo '<font color="#DD0000">'.$perms.'</font>';
				}
			?>
		</td>
	</tr>
	<tr>
		<td><strong>chmod of directory '/media/images'<br>
		</strong></td>
		<td>0777</td>
		<td>
			<?php
				$perms = substr(sprintf('%o', fileperms('./media/images')), -4);
				if ($perms == '0777')
					echo '<font color="#00AA00">'.$perms.'</font>';
				else
				{
					$okay = false;
					echo '<font color="#DD0000">'.$perms.'</font>';
				}
			?>
		</td>
	</tr>
	<tr>
		<td><strong>chmod of directory '/media/ical'<br>
		</strong></td>
		<td>0777</td>
		<td>
			<?php
				$perms = substr(sprintf('%o', fileperms('./media/ical')), -4);
				if ($perms == '0777')
					echo '<font color="#00AA00">'.$perms.'</font>';
				else
				{
					$okay = false;
					echo '<font color="#DD0000">'.$perms.'</font>';
				}
			?>
		</td>
	</tr>
	<tr>
		<td><strong>chmod of directory '/media/rss'<br>
		</strong></td>
		<td>0777</td>
		<td>
			<?php
				$perms = substr(sprintf('%o', fileperms('./media/rss')), -4);
				if ($perms == '0777')
					echo '<font color="#00AA00">'.$perms.'</font>';
				else
				{
					$okay = false;
					echo '<font color="#DD0000">'.$perms.'</font>';
				}
			?>
		</td>
	</tr>
    <tr>
		<td><strong>chmod of directory '/log'<br>
		</strong></td>
		<td>0777</td>
		<td>
			<?php
				$perms = substr(sprintf('%o', fileperms('./log')), -4);
				if ($perms == '0777')
					echo '<font color="#00AA00">'.$perms.'</font>';
				else
				{
					$okay = false;
					echo '<font color="#DD0000">'.$perms.'</font>';
				}
			?>
		</td>
	</tr>
</table>

<p>
	<form action="" method="post">
		
		<!-- Database connection -->
		<input type="hidden" name="host" value="<?php echo $_POST['host']; ?>" />
		<input type="hidden" name="user" value="<?php echo $_POST['user']; ?>" />
		<input type="hidden" name="password" value="<?php echo $_POST['password']; ?>" />
		<input type="hidden" name="database" value="<?php echo $_POST['database']; ?>" />
		<input type="hidden" name="table_prefix" value="<?php echo $_POST['table_prefix']; ?>" />
		
		<!-- Admin user -->
		<input type="hidden" name="admin_create" value="<?php echo $_POST['admin_create']; ?>" />
		<input type="hidden" name="admin_nickname" value="<?php echo $_POST['admin_nickname']; ?>" />
		<input type="hidden" name="admin_email" value="<?php echo $_POST['admin_email']; ?>" />
		<input type="hidden" name="admin_password" value="<?php echo $_POST['admin_password']; ?>" />
		
		<!-- Menu -->
		<input type="hidden" name="menu_login" value="<?php echo $_POST['menu_login']; ?>" />
		<input type="hidden" name="menu_admin" value="<?php echo $_POST['menu_admin']; ?>" />
		<input type="hidden" name="menu_usercp" value="<?php echo $_POST['menu_usercp']; ?>" />
		<input type="hidden" name="menu_pmbox" value="<?php echo $_POST['menu_pmbox']; ?>" />
		<input type="hidden" name="menu_guestlist" value="<?php echo $_POST['menu_guestlist']; ?>" />
		
		<!-- Language -->
		<input type="hidden" name="language" value="<?php echo $_POST['language']; ?>" />	
		
		<input type="hidden" name="step" value="2" />
		<input type="submit" name="back" value="&lt; Back" />
		<input type="submit" name="next" value="Next &gt;" <?php if (!$okay) echo 'disabled'; ?>  />
	</form>
</p>