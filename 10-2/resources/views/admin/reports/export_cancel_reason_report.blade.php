<?php
$filename = 'cancle_reason_report' . date('d-m-Y H:s:i') . '.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"Sr No"
 ."\t"."Reasons for Not Buying"
 ."\t"."Count"
 . "\n";
$i = 1;
foreach ($cancelledReasons as $item)  
{
        
    echo
    $i
    ."\t" . $item->close_reason
    ."\t" . $item->cancel_count
    . "\n";
    $i++;
}