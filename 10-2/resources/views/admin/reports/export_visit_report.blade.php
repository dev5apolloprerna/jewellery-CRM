<?php
$filename = 'customer_visit_report_' . date('d-m-Y_H-i-s') . '.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
    "Sr No"
    . "\t" . "Month"
    . "\t" . "Date"
    . "\t" . "Customer Type"
    . "\t" . "Branch"
    . "\t" . "Sales Person"
    . "\t" . "Customer Name"
    . "\t" . "Customer Phone"
    . "\t" . "Customer Type"
    . "\t" . "Products Viewed"
    . "\t" . "Purchase"
    . "\n";

$i = 1;
foreach ($visits as $visit) {

    $visitDate = \Carbon\Carbon::parse($visit->visit_date);
    $month = $visitDate->format('F Y');
    $date = $visitDate->format('d-m-Y');

    // Get remark safely
    $remark = optional($visit->visitDetails)->remark ?? '';

    // Determine customer type
    $customerType = $visit->customer->custCat->cust_cat_name;

    // Get branch, employee, customer
    $branch = $visit->branch->branch_name ?? 'N/A';
    $employee = $visit->employee->emp_name ?? 'N/A';
    $customer = $visit->customer->customer_name ?? 'N/A';
    $customerPhone = $visit->customer->customer_phone ?? 'N/A';
    $customerType = $visit->customer->custCat->cust_cat_name ?? 'N/A';

    // Get product names as comma-separated
    $productNames = [];
    foreach ($visit->products as $product) {
        $productNames[] = $product->product->product_name ?? 'Product';
    }
    $productsViewed = implode(', ', $productNames);

    // Purchase status
    $purchaseStatus = $visit->products->pluck('status')->contains('ordered') ? 'Yes' : 'No';

    // Output row
    echo
        $i
        . "\t" . $month
        . "\t" . $date
        . "\t" . $customerType
        . "\t" . $branch
        . "\t" . $employee
        . "\t" . $customer
        . "\t" . $customerPhone
        . "\t" . $customerType
        . "\t" . $productsViewed
        . "\t" . $purchaseStatus
        . "\n";

    $i++;
}
