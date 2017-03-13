<?php
require_once __DIR__ . "/case/runcase/RunCase.php";
$request_data = [ 
    "scene" => "deal",
];
$stub_data = [ 
    "zm.zm.data.content.score" => 850,
];
$run_case = new RunCase();
$run_case->createStub($stub_data);
$request_data = $run_case->createRequest($request_data);
$check_data = $run_case->getCheckData($request_data);
print_r($check_data);
$run_case->destroyStub();
