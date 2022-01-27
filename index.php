<?php
require_once __DIR__ . '/vendor/autoload.php';

$get = new getTracker();

$get->url = 'https://cdn.nba.com/static/json/liveData/scoreboard/todaysScoreboard_00.json';

echo json_encode($get->doGET());