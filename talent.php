<?php

/**
 * Use this to generate the talent JSON in data/talent.json
 * IDs are letters (ie: A, B, C...etc)
 * Table SQL is in voting.sql
 * use `make talentJSON` to generate after setting up the config
 */

$db = new PDO('mysql:host=127.0.0.1;dbname=voting', 'root', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: application/json');

$talent = array('talent' => array());

foreach($db->query('SELECT * FROM 15voting') as $row) {

  $_talent = array(
    'id' => $row['id'],
    'name' => $row['name'],
    'title' => $row['title'],
    'summary' => $row['summary']
  );

  // only include these fields if true, since mustache.js doesn't really have if/else statements

  if ($row['presentedBefore'] == TRUE) {
    $_talent['presentedBefore'] = TRUE;
  }

  if ($row['duplicateTalks'] == TRUE) {
    $_talent['duplicateTalks'] = TRUE;
  }

  $talent['talent'][] = $_talent;
}

echo json_encode($talent, true);

$db = null;

?>