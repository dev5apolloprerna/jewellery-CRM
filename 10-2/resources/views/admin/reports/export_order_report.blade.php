<?php
$filename = 'order_report_' . date('d-m-Y H:s:i') . '.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"Sr No"
 ."\t"."Date"
 ."\t"."Branch Name"
 ."\t"."Employee Name"
 ."\t"."Customer Name"
 ."\t"."Mobile"
 ."\t"."Product"
 ."\t"."Karat"
 ."\t"."Colour"
 ."\t"."Weight"
 ."\t"."Reference Tag Number"
 ."\t"."Reference Image"
 ."\t"."Order Given To"
 ."\t"."Delivery Date"
 ."\t"."Remarks"

 . "\n";
$i = 1;
foreach ($orders as $order) {
    foreach ($order->orderDetails as $detail) {
        echo
        $i
        ."\t" . $order->created_at
        ."\t" . ($detail->employee->branch->branch_name ?? 'N/A')
        ."\t" . ($detail->employee->emp_name ?? 'N/A')
        ."\t" . ($order->customer->customer_name ?? 'N/A')
        ."\t" . ($order->customer->customer_phone ?? 'N/A')
        ."\t" . ($detail->product->product_name ?? 'N/A')
        ."\t" . ($detail->karat ?? 'N/A')
        ."\t" . ($detail->color->color_name ?? 'N/A')
        ."\t" . ($detail->weight ?? 'N/A')
        ."\t" . ($detail->refer_tag_number ?? 'N/A')
        ."\t" . ($detail->refer_image_url ? '=HYPERLINK("' . $detail->refer_image_url . '", "View Image")' : 'N/A')

        ."\t" . ($detail->vendor->contact_person ?? 'N/A')
        ."\t" . ($detail->delivery_date )
        ."\t" . ($order->remark ?? '')
        . "\n";

        $i++;
    }
}
