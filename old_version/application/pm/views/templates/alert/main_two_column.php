<?php
/*
 * The complete outside portion of the newsletter. Two column version
 */
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>
     <title><?php print $publicationName; ?> <?php print $alertName; ?></title>
     <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <!-- Do not remove spaces. There are here for IOS fix 
                                                                                                    
    -->                                          
    <style type="text/css">
      .list a {
        color: #cc0000;
        font-family: verdana, helvetica, sans-serif;
        font-size: 11px;
        text-decoration: none;
      }
      a {
        color: #2E6D8F;
        text-decoration: none;
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
                 <table cellspacing="0" border="0"  cellpadding="0" width="600">
                   <tr>
                     <td valign="top" width="420">
                       <!-- MAIN COL -->
                       <?php print $main; ?>
                     </td>
                     <td valign="top" width="180" style="border-left: 1px solid #ccc;">
                       <!-- SIDE COL -->
                       <?php if(isset ($side)){print $side;} ?>
                     </td>
                   </tr>
                 </table>
               </td><!--  / content -->
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
                     <td height="20" valign="top" width="600" colspan="2"><hr style="border: 0; padding-top: 10px; border-top: 1px solid #ffffff; border-bottom: 1px solid #cacaca; margin: 0 20px; border-left:0px solid #ffffff;" /></td>
                   </tr>
                 </table>
               </td><!-- / end footer -->
             </tr>
           </table>
         </td>
       </tr>
     </table>
<!-- Yahoo ie7/ie8 fix -->
    <style type="text/css">
      .list a {
        color: #cc0000;
        font-family: verdana, helvetica, sans-serif;
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
