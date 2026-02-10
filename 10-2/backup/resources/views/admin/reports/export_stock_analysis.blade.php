<?php
$filename = 'stock_analysis_' . date('d-m-Y H:s:i') . '.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"Sr No"
 . "\t" ."Product Name"
 . "\t" ."Viewed"
 . "\t" ."Sold"
 . "\t" ."Conversion ratio"
 . "\t" ."Demand"
 . "\t" ."Product Score"

 . "\n";
$i = 1;
foreach ($products as $product)  
{
        
    echo
    $i
    . "\t" .$product->product_name
    . "\t" .$product->view_product_count
    . "\t" .$product->sold_product_count
    . "\t" .number_format($product->conversion_ratio, 2) 
    . "\t" .number_format($product->demand, 2) 
    . "\t" .number_format($product->product_score, 2)
    . "\n";
    $i++;
}