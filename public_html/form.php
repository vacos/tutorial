<?php
  require_once('./notify.php');
  header('Content-type: application/json');
  $email = $_POST['email'];
  $comment = $_POST['comment'];
  $captcha = $_POST['token'];
  if(!$captcha){
    echo json_encode(array('success' => 'true','message' => 'Please check the the captcha form.'));
    exit;
  }
  $secretKey = "6LfxfNgUAAAAAN-93KYaUdwTLx-ExedQnw6SUiux";

  // post request to server
  $url = 'https://www.google.com/recaptcha/api/siteverify';
  $data = array('secret' => $secretKey, 'response' => $captcha);

  $options = array(
    'http' => array(
      'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      'method'  => 'POST',
      'content' => http_build_query($data)
    )
  );
  $context  = stream_context_create($options);
  $response = json_decode(file_get_contents($url, false, $context),true);
  
  if($response["success"]) {
    // Token Notify
    $token = "jBJB2D7Y83CcfUq5EijwoCmrkoYKu4hFvyJMC7mYhle";

    $str = "มีการโพส Comment โดยมีข้อมูลดังนี้ $email $comment";
    notify_message($str,$token);
    echo json_encode(array('success' => true,'response' => ''));
  } else {
    echo json_encode(array('success' => false,'response' => $response));
  }
?>

