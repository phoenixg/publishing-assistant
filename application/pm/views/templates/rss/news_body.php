<?php
/*
 * $news_headlines = array('title' => 'news title', 'link' => 'link to news');
 *
 */
?><br/>
<tr> <td class="content-copy" valign="top" style="padding: 0 20px; color: #000; font-size: 14px; font-family: Georgia; mso-line-height-rule:exactly; line-height: 20px;" colspan="2">
<table cellpadding="0" cellspacing="0">
<?php
foreach ($news_headlines as $headline) {

  print '<tr>';
  print '<td width="90"  valign="top"><img src="'.$headline['thumbnail'].'" style="border: solid 1px #ccc; padding: 4px;" alt="" /></td>';
  print '<td width="470" valign="top"><div class="overline" style="font-size: 10px; font-family:arial, helvetica, sans-serif; color: #999; letter-spacing: 1px;"><span style="color: #a00000; text-transform:uppercase;">'.$headline['date'].'</span> | '.$headline['category'].'</div>';
  print '<div class="item-title" style="font-size: 15px; margin-bottom: 4px"><strong><a href="'.$headline[link].'">'.$headline[title].'</a></strong></div>';
  print '<div class="teaser" style="color:#444; font-family: arial, sans-serif; font-size: 12px; text-align: left;">'.$headline['description'].'</div>';
  print '<br/><br/></td>';
  print '</tr>';
}
?>
</table>
</td></tr>

