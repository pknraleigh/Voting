<html>
<head>
  <style>
form {
  margin: 0px;
}
.str {
  background-color: #8e8aff;
}
.str1 {
  background-color: #978af5;
}
.str2 {
  background-color: #a18aec;
}
.str3 {
  background-color: #aa8ae2;
}
.str4 {
  background-color: #b48ad8;
}
.str5 {
  background-color: #bd8ace;
}
.str6 {
  background-color: #c68ac5;
}
.str7 {
  background-color: #d08abb;
}
.str8 {
  background-color: #d98ab1;
}
.str9 {
  background-color: #e38aa7;
}
.str10 {
  background-color: #ec8a9e;
}
.str11 {
  background-color: #f68a94;
}
.str12 {
  background-color: #ff8a8a;
}
tr.ignored td {
  color: #7f7f7f;
}
  </style>
</head>
<body>
<?php

function idToName($id) {
  switch($id) {
    case 'A' : return 'Morgan Siem';
    case 'B' : return 'Chris Marlow';
    case 'C' : return "George Smart"; 
    case 'D' : return "Sidd Chopra"; 
    case 'E' : return "Glyn Tomkins"; 
    case 'F' : return "David Williams"; 
    case 'G' : return "Megan Mitzel"; 
    case 'H' : return "Amber Smith"; 
    case 'I' : return "Scott Misner"; 
    case 'J' : return "Geoffrey Neal"; 
    case 'K' : return "Christine Fawley"; 
    case 'L' : return "Cris Cohen"; 
    case 'M' : return "John Smith"; 
    case 'N' : return "Adam Good"; 
    case 'O' : return "Beth Yerxa"; 
    case 'P' : return "Niti Agarwal"; 
    case 'Q' : return "Kristen Gallagher"; 
    case 'R' : return "Kim Curry-Evans"; 
    case 'S' : return "Todd Spiering"; 
    case 'T' : return "Kristen Jeffers"; 
    case 'U' : return "Justin Goeres"; 
    case 'V' : return "Todd Delk"; 
    case 'W' : return "Kevin Flanagan"; 
    default  : return 'ERROR';
  }
} 


function process($votes,$pri,$sec,$ter) {
  global $votes;
  for($i=0;$i<strlen($pri);$i++) {
    for($j=0;$j<strlen($sec);$j++) {
      $votes[$pri[$i]][$sec[$j]]++;
    }
    for($j=0;$j<strlen($ter);$j++) {
      $votes[$pri[$i]][$ter[$j]]++;
    }
  }
  for($i=0;$i<strlen($sec);$i++) {
    for($j=0;$j<strlen($ter);$j++) {
      $votes[$sec[$i]][$ter[$j]]++;
    }
  }
}


$link = mysqli_connect('mysql.philihp.com','philihp_misc','philihp_misc1','philihp_misc');

$result = mysqli_query($link,"SELECT * FROM `pkn_votes` ORDER BY email, time");

echo "<h2>Actual Votes</h2>";
echo "<table border=1 cellspacing=0 cellpadding=3>";
echo "<tr><th>Email</th><th>IP</th><th>Timestamp</th><th>Primary</th><th>Secondary</th><th>Tertiary</th><th colspan=2>Push Butan</th></tr>";
while($row = mysqli_fetch_assoc($result)) {
  if($row['ignored'] != 'Y') {
    process($votes,$row['primary'],$row['secondary'],$row['tertiary']);
    echo "<tr>";
  }
  else {
    echo "<tr class=\"ignored\">";
  }
  echo "<td>{$row[email]}</td><td>{$row[ip]}</td><td>{$row[time]}</td><td>{$row[primary]}</td><td>{$row[secondary]}</td><td>{$row[tertiary]}</td>";
  echo "<td>";
  echo " <form action=\"ignore.php\" method=\"post\">";
  echo "  <input type=\"hidden\" name=\"pkn_vote_id\" value=\"{$row[pkn_vote_id]}\"/>";
  echo "  <input type=\"submit\" value=\"Ignore\"/>";
  echo " </form>";
  echo "</td>";
  echo "<td>";
  echo " <form action=\"unignore.php\" method=\"post\">";
  echo "  <input type=\"hidden\" name=\"pkn_vote_id\" value=\"{$row[pkn_vote_id]}\"/>";
  echo "  <input type=\"submit\" value=\"Unignore\"/>";
  echo " </form>";
  echo "</td>";
  echo "</tr>";
}
echo "</table>";

mysqli_free_result($result);
mysqli_close($link);

echo "<h2>Pairwise Defeat Matrix</h2>";
echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"3\"><thead><tr>";
echo "<th>D</th>";
for($c='A';$c<='W';$c++) {
  echo "<th>[*,{$c}]</th>";
}
echo "</tr></thead>";
echo "<tbody>";
for($c='A';$c<='W';$c++) {
  echo "<tr><th>[{$c},*]</th>";
  for($d='A';$d<='W';$d++) {
    if($c==$d) $votes[$c][$d]=0;
    echo "<td class=\"str",$votes[$c][$d],"\">",$votes[$c][$d],"</td>";
  }
  echo "</tr>";
}
echo "</tbody></table>";

for($i='A';$i<='W';$i++) {
  for($j='A';$j<='W';$j++) {
    if($i!=$j) {
      $strength[$i][$j] = $votes[$i][$j];
    }
    else {
      $strength[$i][$j] = 0;
    }
  }
}
for($i='A';$i<='W';$i++) {
  for($j='A';$j<='W';$j++) {
    if($i!=$j) {
      for($k='A';$k<='W';$k++) {
        if($i!=$k & $j!=$k) {
          $strength[$j][$k] = max($strength[$j][$k], min($strength[$j][$i],$strength[$i][$k]));
        }
      }
    }
  }
}

echo "<h2>Strengths of strongest paths</h2>";
echo "<table border=1 cellspacing=0 cellpadding=3><thead><tr>";
echo "<th>P</th>";
for($c='A';$c<='W';$c++) {
  echo "<th>[*,{$c}]</th>";
}
echo "</tr></thead><tbody>";
for($c='A';$c<='W';$c++) {
  echo "<tr><th>[{$c},*]</th>";
  for($d='A';$d<='W';$d++) {
    echo "<td class=\"str",$strength[$c][$d],"\">",$strength[$c][$d],"</td>";
  }
  echo "</tr>";
}
echo "</table>";

echo "<h2>Ranking of Winners from Most Preferential to Least</h2>";
# calculation of the binary relation O and the winners

for($i='A';$i<='W';$i++) {
  $possible[$i] = 1;
}  

$rank = Array();
$rank_i = 0;
echo "<ol>";
do {
  echo "<li>";
  $rank[++$rank_i] = '';
  for($i='A';$i<='W';$i++) {
    if($possible[$i] == 0) continue;
    $winner = 1;
    for($j='A';$j<='W';$j++) {
      if($possible[$j] == 0) continue;
      if($i!=$j) {
        if($strength[$j][$i] > $strength[$i][$j]) {
          $winner = 0; 
        }
      }
    }
    if($winner == 1) {
      $possible[$i] = 0;
      $rank[$rank_i] .= $i;
      echo $i;
    }
  }

  $done = true;
  for($i='A';$i<='W';$i++) {
    if($possible[$i] == 1) $done = false;
  }
  echo "</li>";
} while($done == false);
echo "</ol>";

echo "<i>...or...</i>";

echo "<ol>";
for($i=1;$i<=$rank_i;$i++) {
  echo "<li>";
  for($j=0;$j<strlen($rank[$i]);$j++) {
    if($j>0) echo ", ";
    echo idToName($rank[$i][$j]);
  }
  echo "</li>";
}
echo "</ol>";
?>
</body>
</html>