<?php
/*
 * Creates the wrapper within the whole body portion of the alert and prints the subheadings. 
 */
?>
<table cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td class="document-title" height="45" valign="top" style="padding: 0 20px; font-family: Georgia; font-size: 20px; font-weight: bold;" colspan="2">
				<br /><?php if(!empty ($sub_heading)){print $sub_heading; print "<br><br>";} ?>
			</td>
		</tr>                  
		<?php print $sections; ?>
	</tbody>
</table>
