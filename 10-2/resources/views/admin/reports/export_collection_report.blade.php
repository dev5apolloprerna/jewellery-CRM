<?php
if($emp != null){
$filename = $emp->emp_name.'_order_file_' . date('d-m-Y H:s:i') . '.xls';
}else{
$filename = 'Sales_staff_order_file_' . date('d-m-Y H:s:i') . '.xls';
}

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"Sr No"
."\t"."Date"
."\t"."Employee Name"
."\t"."Customer Name"
."\t"."Mobile"
."\t"."Item"
."\t"."Karat"
."\t"."Colour"
."\t"."Weight"
."\t"."Size"
."\t"."Tag Number"
."\t"."Image"
."\t"."Order Given To"
."\t"."Delivery Date"
."\t"."Remarks"
."\t"."Paid Amount"
."\t"."Rate"
."\t"."Rate Type"
."\t"."Rate Fix/Open"

 . "\n";
$i = 1;
foreach ($orders as $order) {
    foreach ($order->orderDetails as $detail) 
    {

        if(!empty($detail->delivery_date)){

            $delivery_date=\Carbon\Carbon::parse($detail->delivery_date)->format('d-m-Y');
        }
        else{
            $delivery_date="-";
        }
    echo
        $i
       ."\t" . \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') 
       ."\t" . $detail->employee->emp_name
       ."\t" . $order->customer->customer_name
       ."\t" . $order->customer->customer_phone
       ."\t" . $detail->product->product_name
       ."\t" . $detail->karat 
       ."\t" . $detail->color->color_name
       ."\t" . $detail->weight 
       ."\t" . $detail->size 
       ."\t" . $detail->refer_tag_number 
       ."\t" . ($detail->refer_image_url ? '=HYPERLINK("' . $detail->refer_image_url . '", "View Image")' : 'N/A')
       ."\t" . ($detail->vendor->contact_person ?? '-')
       ."\t" . $detail->delivery_date
       ."\t" . $detail->remark 
       ."\t" . $order->paid_amount 
       ."\t" . $detail->amount
       ."\t" . $detail->rate_type
       ."\t" . $detail->rate_fix_open
        . "\n";

        $i++;
    }
}
