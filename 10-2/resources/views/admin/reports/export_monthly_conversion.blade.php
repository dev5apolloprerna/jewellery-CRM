<?php
$filename = 'monthly_conversion' . date('d-m-Y H:s:i') . '.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"Sr No"
 ."\t"."Month"
 ."\t"."Total Clients Visited"
 ."\t"."Total Clients Purchased"
 ."\t"."Conversion Ratio"
 . "\n";
$i = 1;
foreach ($data as $row)  
{
        
    echo
    $i
    ."\t" . \Carbon\Carbon::parse($row->month . '-01')->format('Y-m')
    ."\t" . $row->total_clients_visited 
    ."\t" . $row->total_clients_sold
    ."\t" . $row->conversion_ratio
    . "\n";
    $i++;
}