<font size="7"><i><?php

$link = mysqli_connect('localhost','philihp','philihp1','philihp');

$stmt = mysqli_prepare($link, 'INSERT INTO `pkn10_votes` (`email`,`ip`,`primary`,`secondary`,`tertiary`) VALUES (?,?,?,?,?)');

mysqli_stmt_bind_param($stmt,"sssss",
   $_POST['email'],
   $_SERVER['REMOTE_ADDR'],
   $_POST['primary'],
   $_POST['secondary'],
   $_POST['tertiary']);

mysqli_stmt_execute($stmt);

if(mysqli_stmt_affected_rows($stmt)) {
  echo "Your vote has been cast.";
}
else {
  echo "No vote was cast. That's no good. That's no good at all.";
}

mysqli_stmt_close($stmt);
mysqli_close($link);

?></i></font>
<br /><br />
Thanks for your support of PechaKucha Night Raleigh.
