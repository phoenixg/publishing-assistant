<table width="100%" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td style="padding: 0 0 0 20px; font-size: 13px; font-family: arial, helvetica, sans-serif;">
        <img src="http://news.sciencemag.org/sciencenow/site-img/snow-logo-155.gif" alt="ScienceNow - Up to the minute news from Science" style="width: 145px; height: auto;" />
        <div style="border: solid 1px #dfdfdf; padding: 8px; width: 128px;">
        
<?php
foreach ($news_headlines as $headline) {
  //print '<img class="thumbnail" src="'.$headline['thumbnail'].'" />';
  //print '<h5>'.$headline['category'].'</h5>';
  print "<a style='display: block; text-align: left;' href='{$headline[link]}'>{$headline[title]}</a></br>\t\t\t\t\n";
}
?>
        </div>
      </td>
    </tr>
  </tbody>
</table>
