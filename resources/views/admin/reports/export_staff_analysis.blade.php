<?php
$filename = 'sales_staff_analysis_' . date('d-m-Y H:s:i') . '.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"Sr No"
 ."\t"."Employee Name"
 ."\t"."Branch Name"
 ."\t"."Clients Attended"
 ."\t"."Clients Converted"
 ."\t"."Conversion Ratio"
 ."\t"."Performance score"

 . "\n";
$i = 1;
foreach ($employees as $emp)  
{
        
    echo
    $i
    ."\t" . $emp->emp_name 
    ."\t" . $emp->branch->branch_name 
    ."\t" . $emp->client_attended_count 
    ."\t" . $emp->client_converted_count 
    ."\t" . number_format($emp->conversion_ratio, 2) . "%"
    ."\t" . number_format($emp->performance_score, 2) 
    . "\n";
    $i++;
}