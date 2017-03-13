<?php
require_once __DIR__ . "/case/runcase/RunCase.php";
$request_data = [ 
    "scene" => "deal",
];
$stub_data = [ 
    "zm.zm.data.content.score" => 850,
];
$diff_data = [
    'status' => -1,
];
echo time()."\n";
$run_case = new RunCase();
$run_case->createStub($stub_data);
$request_data = $run_case->createRequest($request_data);
$check_data = $run_case->getCheckData($request_data);
$check_result = $run_case->checkResult($diff_data, $check_data);
print_r("\n".$check_result);
$run_case->destroyStub();
echo "\n".time();
