<?php
require_once 'functions.php';
if(!empty($_POST)) {
  if (!empty($_POST['button'])) {
    $key = $_POST['button'];
  } else {
    $key = key($_POST);
  }
  switch($key) {
    case 'scienceTOC_create':
      $message = createScienceTOC();
      break;
    case 'editorsChoice_create':
      $message = createEditorsChoice();
      break;
    case 'scienceNewsThisWeek_create':
      $message = createScienceNewsThisWeek();
      break;
    case 'twis_create':
      $message = createTWIS();
      break;
    case 'stmtoc_create':
      $message = createSTMTOC();
      break;
    case 'scienceSignalingTOC_create':
      $message = createScienceSignalingTOC();
      break;
    case 'scienceExpress_create':
      $message = createScienceExpress();
      break;
    case 'scienceNOWDaily_create';
      $message = createScienceNOWDaily();
      break;
    case 'scienceNOWWeekly_create';
      $message = createScienceNOWWeekly();
      break;
    case 'scienceTOC_test':
      $message = sendScienceTOC(false);
      break;
    case 'scienceTOC_live':
      $message = sendScienceTOC(true);
      break;
    case 'editorsChoice_test':
      $message = sendEditorsChoice(false);
      break;
    case 'editorsChoice_live':
      $message = sendEditorsChoice(true);
      break;
    case 'scienceNewsThisWeek_test':
      $message = sendScienceNewsThisWeek(false);
      break;
    case 'scienceNewsThisWeek_live':
      $message = sendScienceNewsThisWeek(true);
      break;
    case 'twis_test':
      $message = sendTWIS(false);
      break;
    case 'twis_live':
      $message = sendTWIS(true);
      break;
    case 'stmtoc_test':
      $message = sendSTMTOC(false);
      break;
    case 'stmtoc_live':
      $message = sendSTMTOC(true);
      break;   
    case 'scienceSignalingTOC_test':
      $message = sendScienceSignalingTOC(false);
      break;
    case 'scienceSignalingTOC_live':
      $message = sendScienceSignalingTOC(true);
      break;
    case 'scienceExpress_test':
      $message = sendScienceExpress(false);
      break;
    case 'scienceExpress_live':
      $message = sendScienceExpress(true);
      break;  
    case 'scienceNOWDaily_test':
      $message = sendScienceNOWDaily(false);
      break;
    case 'scienceNOWDaily_live':
      $message = sendScienceNOWDaily(true);
      break;  
    case 'scienceNOWWeekly_test':
      $message = sendScienceNOWWeekly(false);
      break;
    case 'scienceNOWWeekly_live':
      $message = sendScienceNOWWeekly(true);
      break;    
    default:
      break;
  }
}
unset($_POST);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>eTOC Automation</title>
    <link href="style.css" rel="stylesheet" type="text/css"/>
    <script src="jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $('button').click(function(e) {
          $('button').not(':disabled').addClass('tempDisable').attr('disabled', 'disabled'); // Disable all buttons
          $('#button').val($(this).attr('name'));
          if ($(this).val() == 'live') {
            var title = $(this).siblings('h2').text();
            var keepGoing = confirm("Are you sure you want to send " + title 
              + " to the live list?");
          }
          else {
            var keepGoing = true;
          }
          if (keepGoing) {
            $('#eToc').submit();
          } else {
            // Re-enable the buttons
            $('.tempDisable').removeAttr('disabled').removeClass('tempDisable');
          }
          return keepGoing;      
        });
        
      });
    </script>
            
  </head>
  <body>
    <div id="wrapper">
      <div id="header">
        <div id="science_logo"><img alt="Science" src="images/science_logo.jpg"/></div>
      </div>
      <div id="content">
        <?php if (!empty($message)) :?>
        <div class="alert"><?php print $message;?></div>
        <?php endif;?>
        <form name="eToc" action="" method="post" id="eToc">     
          <div id="scienceTOC" class="row">
            <h2><em>Science</em> Magazine TOC</h2>
            <button type="submit" name="scienceTOC_create" id="scienceTOC_create" value="create">Create email</button>
            <button type="submit" name="scienceTOC_test" id="scienceTOC_test"  value="test">Send test email</button>
            <button type="submit" name="scienceTOC_live" id="scienceTOC_live"  value="live" disabled="disabled">Send live email</button>
          </div>
          <div id="editorsChoice" class="row">
            <h2>Editors' Choice</h2>
            <button type="submit" name="editorsChoice_create" id="editorsChoice_create" value="create">Create email</button>
            <button type="submit" name="editorsChoice_test" id="editorsChoice_test" value="test">Send test email</button>
            <button type="submit" name="editorsChoice_live" id="editorsChoice_live" value="live" disabled="disabled">Send live email</button>
          </div>
          <div id="scienceNewsThisWeek" class="row">
            <h2><em>Science</em> News This Week</h2>
            <button type="submit" name="scienceNewsThisWeek_create" id="scienceNewsThisWeek_create">Create email</button>
            <button type="submit" name="scienceNewsThisWeek_test" id="scienceNewsThisWeek_test">Send test email</button>
            <button type="submit" name="scienceNewsThisWeek_live" id="scienceNewsThisWeek_live" disabled="disabled">Send live email</button>
          </div>
          <div id="twis" class="row">
            <h2>This Week in <em>Science</em></h2>
            <button type="submit" name="twis_create" id="twis_create">Create email</button>
            <button type="submit" name="twis_test" id="twis_test">Send test email</button>
            <button type="submit" name="twis_live" id="twis_live" disabled="disabled">Send live email</button>
          </div>
          <div id="stmtoc" class="row">
            <h2><em>Science Translational Medicine</em> TOC</h2>
            <button type="submit" name="stmtoc_create" id="stmtoc_create">Create email</button>
            <button type="submit" name="stmtoc_test" id="stmtoc_test">Send test email</button>
            <button type="submit" name="stmtoc_live" id="stmtoc_live" disabled="disabled">Send live email</button>
          </div>
          <div id="scienceSignalingTOC" class="row">
            <h2><em>Science Signaling</em> TOC</h2>
            <button type="submit" name="scienceSignalingTOC_create" id="scienceSignalingTOC_create">Create email</button>
            <button type="submit" name="scienceSignalingTOC_test" id="scienceSignalingTOC_test">Send test email</button>
            <button type="submit" name="scienceSignalingTOC_live" id="scienceSignalingTOC_live" disabled="disabled">Send live email</button>
          </div>
          <div id="scienceExpress" class="row">
            <h2><em>Science</em> Express Notification</h2>
            <button type="submit" name="scienceExpress_create" id="scienceExpress_create">Create email</button>
            <button type="submit" name="scienceExpress_test" id="scienceExpress_test">Send test email</button>
            <button type="submit" name="scienceExpress_live" id="scienceExpress_live" disabled="disabled">Send live email</button>
          </div>
          <div id="scienceNOWDaily" class="row">
            <h2><em>Science</em> NOW Daily Alert</h2>
            <button type="submit" name="scienceNOWDaily_create" id="scienceNOWDaily_create">Create email</button>
            <button type="submit" name="scienceNOWDaily_test" id="scienceNOWDaily_test">Send test email</button>
            <button type="submit" name="scienceNOWDaily_live" id="scienceNOWDaily_live" disabled="disabled">Send live email</button>
          </div>
          <div id="scienceNOWWeekly" class="row">
            <h2><em>Science</em> NOW Weekly Alert</h2>
            <button type="submit" name="scienceNOWWeekly_create" id="scienceNOWWeekly_create">Create email</button>
            <button type="submit" name="scienceNOWWeekly_test" id="scienceNOWWeekly_test">Send test email</button>
            <button type="submit" name="scienceNOWWeekly_live" id="scienceNOWWeekly_live" disabled="disabled">Send live email</button>
          </div>
          <input type="hidden" name="button" id="button" value=""/>
        </form>
      </div>
    </div>
  </body>
</html>
