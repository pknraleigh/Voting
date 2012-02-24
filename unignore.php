<?php

$link = mysqli_connect('localhost','philihp','philihp1','philihp');

$stmt = mysqli_prepare($link, 'UPDATE `pkn10_votes` SET `ignored`=null WHERE `pkn_vote_id`=?');

mysqli_stmt_bind_param($stmt,"d",
   $_POST['pkn_vote_id']);

mysqli_stmt_execute($stmt);

header("Location: winners.php");

mysqli_stmt_close($stmt);
mysqli_close($link);

?>
