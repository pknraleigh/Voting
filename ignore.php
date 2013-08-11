<?php

require 'config.inc.php';

$db = db();

$ignored = (isset($_POST['unignore']) && !empty($_POST['unignore']))? NULL : 'Y';

$stmt = $db->prepare('UPDATE `votes` SET `ignored`=:ignored WHERE `id`=:id');
$stmt->execute(array(
  'id' => $_POST['id'],
  'ignored' => $ignored
));

$db = null;

header("Location: winners.php");

?>
