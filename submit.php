<?php

require 'config.inc.php';

header('Content-Type: applicaiton/json');

$data = $_POST;

$email = $data['email'];
$primary = $data['primary'];
$secondary = $data['secondary'];
$tertiary = $data['tertiary'];

$db = db();

checkForVote($data['email'], $db);

$stmt = $db->prepare('INSERT INTO `votes` (`email`,`primary`,`secondary`,`tertiary`)
                      VALUES (:email, :primary, :secondary, :tertiary)');
$stmt->execute(array(
  'email' => $email,
  'primary' => $primary,
  'secondary' => $secondary,
  'tertiary' => $tertiary
));

$status = ($stmt->rowCount() > 0)? 1 : 0;

if ($status === 1) {
  echo json_encode(array('status' => '1', 'message' => 'Your vote has been cast.'));
}
else {
 echo json_encode(array('status' => '0', 'message' => "No vote was cast. That's no good. That's no good at all."));
}

$db = null;

function checkForVote($email=null, $db=null) {
  if ($db == null || $email == null) {
    return false;
  }

  $stmt = $db->prepare('SELECT `email` FROM `votes` WHERE email = :email');

  $stmt->execute(array(
    'email' => $email
  ));

  if ($stmt->rowCount() > 0) {
    echo json_encode(array('status' => '-1', 'message' => 'You have already voted! Thanks so much! :)'));
    exit;
  }
}

?>