<?php
require_once('EloquaServiceClient.php');
define('WSDL', 'https://secure.eloqua.com/API/1.2/EmailService.svc?wsdl');
define('USERNAME', 'AAAS\API.USER');
define('PASSWORD', 'Flam8685fate');
//define('LOG', '/var/www/html/etoc-log.txt');
define('LOG', '/inet/www/codeigniter-v2.1.2/application/pm/output/logs/etoc-log.txt');

// $test_mode = true; // If in test mode, emails will have 'TEST-NewSig' at front and go to a separate Email Group

/**
 * Returns the HTML content of a URL for use in creating an HTML email
 *
 * @param string $url URL of HTML content to import
 * @return string HTML Content
 */
function getEmailHTML($url) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $html = curl_exec($ch);
  if (curl_error($ch)) {
    die(curl_error($c));
  }
  curl_close($ch);
  if($log = fopen(LOG,'a')) {
    fwrite($log, time() . ": Retrieved email HTML from {$url}\n");
    fclose($log);
  }
  return $html;
}

/**
 * Gets an existing Eloqua Redirect Link for a url or creates a new one
 * 
 * Patterns matched:
 *  0: full link
 *  1: attributes before the href
 *  2: type of quote, if any, before href value
 *  3: link URL
 *  4: Additional code for Eloqua direct link, hard-coded into some email templates
 *     (Using Eloqua redirect links instead)
 *  5: hash (will come after the Eloqua direct link code, but needs to be included in the redirect)
 *  6: attribrutes after the href
 *  7: Text content of link
 *  8: Ending tag. Some links are missing the closing a tag and end with a line break
 *  
 * @global EloquaServiceClient $client
 * @param string $url
 * @return string URL of redirect link 
 */
function getRedirectLink($link) {
  $client = new EloquaServiceClient(WSDL, USERNAME, PASSWORD);  
  global $existing_redirect_links;
  
  //Add the Eloqua redirect links only for links that can be parsed
  if(empty($link[3]) || stristr($link[3], '<span')) {
    return $link[0];
  }
  
  $trimmed_link = rtrim($link[3], '/');
  if(!empty($link[5])) {
    $trimmed_link .= $link[5];
  }
  // Check the redirect links already found first to avoid making multiple calls
  // for the same URL
  if(isset($existing_redirect_links[$trimmed_link])) {
    $directed_link = $existing_redirect_links[$trimmed_link];
  } else {
    // Look up the redirect link
    if($log = fopen(LOG,'a')) {
      fwrite($log, time() . ": Begin searching for redirect link " . $link[3] ."\n");
      fclose($log);
    }
    $new_link = false;
    $max_retries = 5;
    $retry_count = 0;
    while(!$new_link && $retry_count < $max_retries) {
      try {
        $new_link = $client->CreateRedirectLink($link[3]);
      } catch (SoapFault $soapFault) {
        if ($soapFault->faultstring != 'Could not connect to host') {
          $message = "Unable to create redirect links for " . $link[3] . '<br/>';
          $message .= $soapFault->getMessage();
          die($message);
          sleep(1);
          if($log = fopen(LOG,'a')) {
            fwrite($log, time() . ": Failed to connect. Try #{$retry_count}\n");
            fclose($log);
          }
        }
      }
      $retry_count++;
    }
    if($retry_count == $max_retries) {
      $message = "Unable to create redirect links for " . $link[3] . '<br/>';
      $message .= "Could not connect to host after 5 attempts";
      die($message);
    }
    if($new_link) {
      $directed_link = $new_link->CreateRedirectLinkResult->Tag;
      $existing_redirect_links[$trimmed_link] = $directed_link;
    }
    
  }
  unset($client);
  if($directed_link) {
    return '<a href="' . $directed_link . '"' . $link[1] . $link[6] . '>' . $link[7] . '</a>';
  } else {
    return $link[0];
  }
}

/**
 * Creates an HTML email in Eloqua
 *
 * @global EloquaServiceClient $client
 * @param string $name Name of email in Eloqua
 * @param string $subject Email subject line
 * @param int $group Id of Email Group in Eloqua
 * @param string $html HTML content of email
 * @param string $from From name for email
 * @return int  Id of email in Eloqua
 */
function createHTMLEmail($name, $subject, $group, $html, $from) {
$existing_redirect_links = array();
  $client = new EloquaServiceClient(WSDL, USERNAME, PASSWORD);
  global $test_mode;
  
  if($test_mode) {
    $name = 'SCI-TEST-Newsig-' . $name;
    $group = 59;
  }
  
  // Regular expression for links. See getRedirectLink()
  $pattern = '/<a\s([^>]*)href=(\"|\'??)([^\"\'>]*?)(\?elq=\<span class=eloquaemail\>recipientid\<\/span\>)?(#[^\"\' >]*)?\\2([^>]*)>(.*)(<\/a>|<br\s?\/?>)/siU';
  
  $html = preg_replace_callback($pattern, 'getRedirectLink', $html);
  $email = new EmailDetails($name, $subject, $group, $html, $from, array('HeaderId'=>19, 'FooterId'=>47));
  if($log = fopen(LOG,'a')) {
    fwrite($log, time() . ": Create email\n");
    fclose($log);
  }
  $emailResult = false;
  $retry_count = 0;
  $max_retries = 5;
  while(!$emailResult && $retry_count < $max_retries) {
    try {
      $emailResult = $client->CreateHtmlEmail($email);
    } catch (SoapFault $soapFault) {
      if($soapFault->faultString != 'Could not connect to host') {
        $message = "Unable to create the email<br/>";
        $message .= $soapFault->getMessage();
        die ($message);
      }
      sleep(1);
    }
    $retry_count++;
  }
  if ($retry_count == $max_retries) {
    die ("Unable to create the email after 5 attempts");
  }
  $emailId = $emailResult->CreateHtmlEmailResult;
  return "{$name} ({$emailId}) created";
}


/**
 * Schedules a deployment of an email to be sent in two minutes
 *
 * @global EloquaServiceClient $client Eloqua SOAP client
 * @param string $name Name of deployment
 * @param string $emailId Eloqua id of email to send
 * @param string $listId Eloqua id of list to send email to
 * @param boolean $live Whether this is a live send
 * @return type 
 */
function scheduleDeployment($name, $listId, $live=false) {
  $client = new EloquaServiceClient(WSDL, USERNAME, PASSWORD);
  global $test_mode;
  
  /** FOR TESTING **/
  if($test_mode) {
    $name = 'TEST-Newsig-' . $name;
    $listID = 543;
  }
  $live = false;
  /*****************/
  
  try {
    $result = $client->SearchEmail($name);
  } catch (SoapFault $soapFault) {
    return "<span class=\"err\">Email {$name} not found</span>";
  }
  if (empty($result->SearchEmailResult->Email)) {
    return "<span class=\"err\">Email {$name} not found</span>"; 
  } elseif (is_array($result->SearchEmailResult->Email)) {
    return "<span class=\"err\">Multiple copies of {$name} found</span>";
  }
  $emailId = $result->SearchEmailResult->Email->Id;
  $suffix = $live ? '_live' : '_test';
  
  $settings = new DeploymentSettings(array(
      'Name' => str_replace(array(' ','-'), '_', $name) . $suffix,
      'EmailId' => $emailId,
      'DeploymentDate' => strtotime('+2 mins'),
      'DistributionListId' => $listId,
    ));

  try {
    $deploy = $client->Deploy($settings);
  } catch (SoapFault $soapFault) {
    return('<span class="error">Unable to schedule the deployment</span>');
  }
  $deployId = $deploy->DeployResult->Id;
  $date = $deploy->DeployResult->Options->DeploymentDate;
  return "{$name} successfully scheduled to send to list {$listId} on {$date} ({$deployId})";
}

/**
 * Send the emails
 * @param boolean $live Whether this is a live send
 * @return string Success or error message 
 */
function sendScienceTOC($live=false) {
  $name = 'Science TOC - ' . date("Ymd");
  $listId = $live ? 83 : 297;
  return scheduleDeployment($name, $listId, $live);
}
function sendEditorsChoice($live=false) {
  $name = "Editors Choice - ".date('Ymd');
  $listId = $live ? 81 : 295;
  return scheduleDeployment($name, $listId, $live);
}
function sendScienceNewsThisWeek($live=false) {
  $name = 'Science News This Week - ' . date('Ymd');
  $listId = $live ? 84 : 298;
  return scheduleDeployment($name, $listId, $live);
}
function sendTWIS($live=false) {
  $name = 'TWIS - ' . date('Ymd');
  $listId = $live ? 89 : 303;
  return scheduleDeployment($name, $listId, $live);
}
function sendSTMTOC($live=false) {
  $name = 'STM TOC - ' . date('Ymd');
  $listId = $live ? 88 : 300;
  return scheduleDeployment($name, $listId, $live);
}
function sendScienceSignalingTOC($live=false) {
  $name = 'Signaling TOC - ' . date('Ymd');
  $listId = $live ? 87 : 299;
  return scheduleDeployment($name, $listId, $live);
}
function sendScienceExpress($live=false) {
  $name = 'Science Express - ' . date('Ymd');
  $listId = $live ? 82 : 296;
  return scheduleDeployment($name, $listId, $live);
}
function sendScienceNOWDaily($live=false) {
  $name = 'Science News Daily - ' . date('Ymd');
  $listId = $live ? 85 : 301;
  return scheduleDeployment($name, $listId, $live);
}
function sendScienceNOWWeekly($live=false) {
  $name = 'Science News Weekly - ' . date('Ymd');
  $listId = $live ? 86 : 302;
  return scheduleDeployment($name, $listId, $live);
}

/**
 * Utility function to print the details of a deployment
 * 
 * @global EloquaServiceClient $client
 * @param int $id Deployment Id
 */
function printDeploymentDetails($id) {
  $client = new EloquaServiceClient(WSDL, USERNAME, PASSWORD);
  
  $result = $client->GetDeployment($id);
  var_dump($result);
}
