<?php
/*
* The complete outside portion of the newsletter. One column version
*/
?>
<html>
  <head>
    <title><?php print $publicationName; ?> <?php print $alertName; ?></title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <!-- Do not remove spaces. There are here for IOS fix 
                                                                                                    
    -->                                          
    <style type="text/css">
      .list a {
        color: #cc0000;
        text-transform: uppercase;
        font-family: Verdana;
        font-size: 11px;
        text-decoration: none;
      }
      a {
        text-decoration: none;
        color: #2E6D8F;
      }
      a:hover {
        text-decoration: underline;
      }
      ReadMsgBody {width: 100%;}
      ExternalClass {width: 100%;}
      span.yshortcuts { color:#631719; background-color:none; border:none;}
      span.yshortcuts:hover,
      span.yshortcuts:active,
      span.yshortcuts:focus {color:#631719; background-color:none; border:none;}
      div, p, a, li, td { -webkit-text-size-adjust:none; } 
    </style>
  </head>
  <body marginheight="0" topmargin="0" marginwidth="0" bgcolor="#cccccc" leftmargin="0" style="background-image: url(http://www.sciencemag.org/site/icons_shared/external/alert-bg.gif); background-attachment: fixed; background-color: #cccccc;" >
    <!-- wrapper -->
    <table cellspacing="0" border="0" style="background-image: url(http://www.sciencemag.org/site/icons_shared/external/alert-bg.gif); background-attachment: fixed;" cellpadding="0" width="100%">
       <tr>
         <td valign="top">
           <table cellspacing="0" border="0" align="center" style="background: #fff; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-right: 1px solid #ccc; border-left: 1px solid #ccc;" cellpadding="0" width="600">
             <tr>
              <td valign="top">
                <!-- header -->
                <?php print $header; ?>
                <!-- / header -->
              </td>
            </tr>
            <tr>
              <td><!-- content -->
                <table cellspacing="0" border="0"  cellpadding="0" width="100%">
                  <tr width="100%" cellspacing="0" border="0">
                    <td valign="top" width="100%">
                      <table cellspacing="0" cellpadding="0">
                        <tbody>
                          <tr>
                            <td>
                              <?php print $main; ?>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </table>
                <!--  / content -->
              </td>
            </tr>
            <tr>
              <td valign="top"><!-- footer -->
                <table cellspacing="0" border="0" cellpadding="0" width="600">
                  <?php
                     $footer = read_file('application/pm/output/ads/'.$jname."_".$alert.'_footer.html');
                     if(strlen($footer) >= 10){
                        print '<tr><td height="20" valign="top" width="600" colspan="3"><hr style="border: 0; padding-top: 10px; border-top: 1px solid #ffffff; border-bottom: 1px solid #cacaca; margin: 10px 20px; border-left:0px solid #ffffff;" /></td></tr>';
                        print '<tr><td colspan="2" style="padding: 12px 20px; background-color: #efefef;">'.$footer.'</td></tr>';
                     }
                  ?>
                  <tr>
                    <td valign="top" colspan="2"><hr style="border: 0; padding-top: 10px; border-top: 1px solid #ffffff; border-bottom: 1px solid #cacaca; margin: 10px 20px; border-left:0px solid #ffffff;" /></td>
                  </tr>
                </table><!-- / end footer -->
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
<!-- Yahoo ie7/ie8 fix -->
    <style type="text/css">
      .list a {
        color: #cc0000;
        text-transform: uppercase;
        font-family: Verdana;
        font-size: 11px;
        text-decoration: none;
      }
      a {
        text-decoration: none;
        color: #2E6D8F;
      }
      a:hover {
        text-decoration: underline;
      }
      ReadMsgBody {width: 100%;}
      ExternalClass {width: 100%;}
      span.yshortcuts { color:#631719; background-color:none; border:none;}
      span.yshortcuts:hover,
      span.yshortcuts:active,
      span.yshortcuts:focus {color:#631719; background-color:none; border:none;}
      div, p, a, li, td { -webkit-text-size-adjust:none; } 
    </style>
<!-- end fix -->
  </body>
</html>
