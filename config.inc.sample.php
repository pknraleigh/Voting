<?php

define('DB_HOST', 'localhost');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_NAME', '');


function db() {
  $_mysqlURI = sprintf("mysql:host=%s;dbname=%s", DB_HOST, DB_NAME);

  $_db = new PDO($_mysqlURI, DB_USER, DB_PASS);
  $_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  return $_db;
}
