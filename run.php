<?php
$files = shell_exec('ls -l case|grep "^-"|awk -F" " \'{print $9}\'');
$case_arr = explode("\n", $files);
$exit_status = 0;
$status_arr = [
    0 => "SUCCESS",
    1 => "FAILED",
];
foreach ($case_arr as $case) {
    if (!$case) {
        continue;
    }
    $status = shell_exec("php case/$case");
    if (1 == $status) {
        $exit_status = $status;
    }
    echo $case."\t"."[".$status_arr[$status]."]\n";
}
if ($exit_status) {
    exit(1);
}
