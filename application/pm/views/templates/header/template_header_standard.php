<table cellspacing="0" border="0" cellpadding="0" width="600">
  <?php
      $header = read_file('application/pm/output/ads/'.$jname."_".$alert.'_header.html');
						if(strlen($header) >= 10){
									print '<tr><td colspan="2" style="padding: 12px 20px; background-color: #efefef;">'.$header.'</td></tr>';
      }
   ?>
  <tr>
    <td height="20" valign="top" width="600" colspan="2">
      <hr style="border: 0; padding-top: 10px; border-top: 1px solid #ffffff; border-bottom: 1px solid #cacaca; margin: 0 20px; border-left:0px solid #ffffff;" />

    </td>
  </tr>
  <tr>
    <td colspan="2" style="padding: 0 20px;">
      <table cellspacing="0" border="0" cellpadding="0" width="100%" style="height: 75px;">
        <tbody>
          <tr style="background-color: #a00000;">
            <td width="20%" style="padding-top: 6px; padding-right: 0px; padding-bottom: 0px; padding-left: 16px;" height="75"><img src="http://www.sciencemag.org/site/icons_shared/external/logo-<?php print $jname?>.png" alt="Science/AAAS" /></td>
            <td width="60%" style="padding-top: 6px; padding-right: 0px; padding-bottom: 0px; padding-left: 16px;" height="75"><center>
                <em style="font-size: 18px; font-weight:bold; font-family: 'Times New Roman', Times, serif; color: #fff;"><?php print $publicationName; ?></em>
                <div style="font-size: 32px; font-family: 'Times New Roman', Times, serif; color: #fff;"><?php print $alertName; ?></div>
              </center>
            </td>
            <td width="20%" style="padding-top: 6px; padding-right: 0px; padding-bottom: 0px; padding-left: 16px;" height="75">&nbsp;</td>
          </tr>
        </tbody>
      </table>
    </td>
  </tr>
  <tr>
    <td height="10" valign="top" width="600" colspan="2">
      <hr style="border: 0; padding-top: 10px; border-top: 1px solid #ffffff; border-bottom: 1px solid #cacaca; margin: 0 20px; border-left:0px solid #ffffff;" />
    </td>
  </tr>
  <tr>
    <td class="header-bar" valign="bottom" style="color: #666; font-family: Verdana; font-size: 10px; text-transform: uppercase; padding: 0 20px; " width="160" height="20">
      <?php print $pubdate; ?>
    </td>
    <td class="header-bar" valign="bottom" style="color: #666; font-family: Verdana; font-size: 10px; text-transform: uppercase; padding: 0 20px;  text-align: right;"  height="20" width="440">
      Volume <?php print $vol; ?>, Issue <?php print $issue; ?>
    </td>
  </tr>
  <tr>
    <td height="10" valign="bottom" width="600" colspan="2">
      <hr style="border: 0; padding-top: 10px; border-top: 1px solid #ffffff; border-bottom: 1px solid #cacaca; margin: 0 20px; border-left:0px solid #ffffff;" />
    </td>
  </tr>
</table>
