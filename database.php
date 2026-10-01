<?php

require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");

$db = $client->selectDatabase("job_portal");

$users = $db->users;
$jobs = $db->jobs;
$applications = $db->applications;
$messages = $db->message;

?>