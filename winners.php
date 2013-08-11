<html>
<head>
  <style>
    form {margin:0px;}
    tr.ignored td {color:#7f7f7f;}
  </style>
  <title>Winners</title>
</head>
<body>
<?php

error_reporting(0);

require 'config.inc.php';

function idToName($id) {
  switch($id) {
    case 'A' : return 'Heather Leah';
    case 'B' : return 'Kyle Berner';
    case 'C' : return 'Kristen Baumlier';
    case 'D' : return 'Jason Hibbets';
    case 'E' : return 'David Matthew Parker';
    case 'F' : return 'Geoffrey Neal';
    case 'G' : return 'Todd Delk';
    case 'H' : return 'John Lowe';
    case 'I' : return 'Mike Zhu';
    case 'J' : return 'George Smart';
    case 'K' : return 'Jamie Katz';
    case 'L' : return 'Michiel Doorn';
    case 'M' : return 'Nathan Blaker';
    case 'N' : return 'Maria Droujkova';
    case 'O' : return 'Teri Saylor';
    case 'P' : return 'Sidd Chopra';
    case 'Q' : return 'Geoffrey Neal';
    case 'R' : return 'Katie Connors';
    case 'S' : return 'Alex Glenn';
    case 'T' : return 'Brittany Iery & Susannah Brinkley';
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

$db = db();

$stmt = $db->query('SELECT * FROM `votes` ORDER BY email, time');

echo "<h2>Actual Votes</h2>";
echo "<table border=1 cellspacing=0 cellpadding=3>";
echo "<tr><th>Email</th><th>Timestamp</th><th colspan=2>Push Butan</th></tr>";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  if($row['ignored'] != 'Y') {
    process($votes,$row['primary'],$row['secondary'],$row['tertiary']);
    echo "<tr>";
    $ignored = null;
    $unignore = 'disabled';
  }
  else {
    echo "<tr class=\"ignored\">";
    $ignored = 'disabled';
    $unignore = null;
  }
  echo "<td>{$row['email']}</td><td>{$row['time']}</td>";
  echo "<td>";
  echo " <form action=\"ignore.php\" method=\"post\">";
  echo "  <input type=\"hidden\" name=\"id\" value=\"{$row['id']}\"/>";
  echo "  <input type=\"submit\" {$ignored} value=\"Ignore\"/>";
  echo " </form>";
  echo "</td>";
  echo "<td>";
  echo " <form action=\"ignore.php\" method=\"post\">";
  echo "  <input type=\"hidden\" name=\"id\" value=\"{$row[id]}\"/>";
  echo "  <input type=\"hidden\" name=\"unignore\" value=\"true\"/>";
  echo "  <input type=\"submit\" {$unignore} value=\"Unignore\"/>";
  echo " </form>";
  echo "</td>";
  echo "</tr>";
}
echo "</table>";

$db = null;

for($i='A';$i<='T';$i++) {
  for($j='A';$j<='T';$j++) {
    if($i!=$j) {
      $strength[$i][$j] = $votes[$i][$j];
    }
    else {
      $strength[$i][$j] = 0;
    }
  }
}
for($i='A';$i<='T';$i++) {
  for($j='A';$j<='T';$j++) {
    if($i!=$j) {
      for($k='A';$k<='T';$k++) {
        if($i!=$k & $j!=$k) {
          $strength[$j][$k] = max($strength[$j][$k], min($strength[$j][$i],$strength[$i][$k]));
        }
      }
    }
  }
}

echo "<h2>Ranking of Winners from Most Preferential to Least</h2>";
# calculation of the binary relation O and the winners

for($i='A';$i<='T';$i++) {
  $possible[$i] = 1;
}

$rank = Array();
$rank_i = 0;
do {
  $rank[++$rank_i] = '';
  for($i='A';$i<='T';$i++) {
    if($possible[$i] == 0) continue;
    $winner = 1;
    for($j='A';$j<='T';$j++) {
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
    }
  }

  $done = true;
  for($i='A';$i<='T';$i++) {
    if($possible[$i] == 1) $done = false;
  }
} while($done == false);

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