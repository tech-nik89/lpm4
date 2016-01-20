<div class="headline">{$lang.event_add}</div>

<form action="" method="post">

	<table width="100%" border="0" cellspacing="1" cellpadding="5">
		
		<tr class="highlight_row">
			<td width="25%"><strong>{$lang.name}:</strong></td>
			<td><input type="text" name="name" style="width:70%;" /></td>
		</tr>
		
		<tr>
			<td><strong>{$lang.description}:</strong></td>
			<td><input type="text" name="description" style="width:70%;" /></td>
		</tr>
		
		<tr class="highlight_row">
			<td><strong>{$lang.begin}:</strong></td>
			<td>
				<p>{html_select_date prefix="Start_" end_year="+8"}</p>
				<p>{html_select_time prefix="Start_" display_seconds=false} {$lang.o_clock}</p>
			</td>
		</tr>
		
		<tr>
			<td><strong>{$lang.end}:</strong></td>
			<td>
				<p>{html_select_date prefix="End_" end_year="+8"}</p>
				<p>{html_select_time prefix="End_" display_seconds=false} {$lang.o_clock}</p>
			</td>
		</tr>
		
		<tr class="highlight_row">
			<td><strong>{$lang.reg_begin}:</strong></td>
			<td>
				<p>{html_select_date prefix="Start_Reg_" end_year="+8"}</p>
				<p>{html_select_time prefix="Start_Reg_" display_seconds=false} {$lang.o_clock}</p>
			</td>
		</tr>
		
		<tr>
			<td><strong>{$lang.reg_end}:</strong></td>
			<td>
				<p>{html_select_date prefix="End_Reg_" end_year="+8"}</p>
				<p>{html_select_time prefix="End_Reg_" display_seconds=false} {$lang.o_clock}</p>
			</td>
		</tr>
		
		<tr class="highlight_row">
			<td><strong>{$lang.event_login_active}:</strong></td>
			<td><input type="checkbox" name="login_active" value="1" /></td>
		</tr>
		
		<tr>
			<td><strong>{$lang.min_age}:</strong></td>
			<td>{html_options name=min_age options=$min_age} {$lang.years}</td>
		</tr>
		
		<tr class="highlight_row">
		  <td><strong>{$lang.seats}:</strong></td>
		  <td><input type="text" name="seats" value="16" /></td>
	    </tr>
        
		<tr>
			<td><strong>{$lang.pay_to_be_counted}:</strong></td>
			<td><input type="checkbox" name="free" value="1" checked="checked" /></td>
		</tr>
		
		<tr class="highlight_row">
			<td><strong>{$lang.agb}:</strong></td>
			<td><textarea name="agb" style="width:100%; height:200px;"></textarea></td>
		</tr>
		
		<tr>
			<td><strong>{$lang.credits}:</strong></td>
			<td><input type="text" name="credits" value="0" /></td>
		</tr>
		
	</table>

	<p>
		<input type="submit" name="add" value="{$lang.add}" />
	</p>
	
</form>