<?php
require 'vendor/autoload.php';
require_once('Curl_parser.php');

use PHPHtmlParser\Dom;

$url = $_POST['url'];
$curl_parser = new Curl_parser();
$parsed_html = $curl_parser->Fetch_URL($url);

$output = fopen("testfile.html", "w");
fwrite($output, $parsed_html);
fclose($output);
// dom object
$dom = new Dom;
// load Dom from file
$dom->loadFromFile('testfile.html');
// reading content from file where class name is MsoNormalTable

$subject = $dom->find('table')->find('h2');
// print_r($subject->innerHtmsl); exit;
if (strpos($subject, 'An adjustment for your recent Walmart.com order') !== false) {

    $contents = $dom->find('table')->find('table')[4];
    if (!empty($contents)) {
        //writing new file with new content
        $extracted_div = fopen("extracted.html", "w");
        fwrite($extracted_div, $contents->innerHtml);
        fclose($extracted_div);

        $dom->loadFromFile('extracted.html');
        $extracted_product = $dom->find('span')[4];
        // print_r($extracted_product);
        $extracted_adjustment = $dom->find('span')[5];
        $extracted_reason = $dom->find('span')[6];
        $extracted_data = array(
            'product' => $extracted_product->innerHtml,
            'adjustment' => $extracted_adjustment->innerHtml,
            'reason' => $extracted_reason->innerHtml
        );

        print_r($extracted_data);
    } else {
        echo 'Response Failed Or Required Query Not Found';
    }
} elseif (strpos($subject, 'Your order has been delivered:')) {
    $contents = $dom->find('table')->find('table')[7];
    $address = $dom->find('table')->find('table')[6]->find('p')[2];
    $tracking_info_company = $dom->find('table')->find('table')[6]->find('p')[3]->find('span');
    $tracking_info = $dom->find('table')->find('table')[6]->find('p')[3]->find('span')[1];

    $address =  preg_replace('/<br \/>/iU', '', $address->innerHtml);
    $tracking  = $tracking_info_company . ' ' . $tracking_info->innerHtml;
    //writing new file with new content
    if (!empty($contents)) {
        $extracted_div = fopen("extracted.html", "w");
        fwrite($extracted_div, $contents->innerHtml);
        fclose($extracted_div);

        $dom->loadFromFile("extracted.html");
        $extracted_product = $dom->find('span')[6]->innerHtml;
        $extracted_price = $dom->find('span')[5]->innerHtml;
        $extracted_quantity = $dom->find('span')[7]->innerHtml;
        $extracted_total = $dom->find('span')[8]->innerHtml;

        $extracted_data = array(
            'product' => $extracted_product,
            'unitprice' => $extracted_price,
            'quantity' => $extracted_quantity,
            'total' => $extracted_total,
            'address' => $address,
            'tracking_id' => $tracking
        );
        print_r($extracted_data);
    } else {
        echo "Response Failed or Query Not Found";
    }
} elseif (strpos($subject, 'Refund notice for your recent Walmart.com order ')) {
    $contents = $dom->find('table')->find('table')[4];
    // print_r($contents->innerHtml);
    if (!empty($contents)) {

        $extracted_div = fopen("extracted.html", "w");
        fwrite($extracted_div, $contents->innerHtml);
        fclose($extracted_div);

        $dom->loadFromFile("extracted.html");
        $extracted_price = $dom->find('p')[1]->find('span')->innerHtml;
        $extracted_orderNo = $dom->find('span')[3]->innerHtml;
        $extracted_orderDate = $dom->find('span')[5]->innerHtml;
        $extracted_price = (float) filter_var($extracted_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $extracted_price = '$' . $extracted_price;

        $extracted_data = array(
            'price' => $extracted_price,
            'orderNo' => $extracted_orderNo,
            'orderDate' => $extracted_orderDate
        );
        print_r($extracted_data);
    } else {
        echo "Response Failed or Query Not Found";
    }
} elseif (strpos($subject, 'Order received. Arrives')) {

    $delivery_date = $dom->find('table')->find('table')[5]->find('span')[1]->innerHtml;
    $delivery_name = $dom->find('table')->find('table')[5]->find('span')[5]->innerHtml;
    $delivery_address = $dom->find('table')->find('table')[5]->find('span')[6] . '' .
        $dom->find('table')->find('table')[5]->find('span')[7] . ' ' .
        $dom->find('table')->find('table')[5]->find('span')[8];
    $item = $dom->find('table')->find('table')[6]->find('span')[4]->innerHtml;
    $quantity = $dom->find('table')->find('table')[6]->find('span')[5]->innerHtml;
    $unit_price = $dom->find('table')->find('table')[6]->find('span')[3]->innerHtml;
    $total = $dom->find('table')->find('table')[6]->find('span')[6]->innerHtml;
    $shipping = $dom->find('table')->find('table')[7]->find('span')[4]->innerHtml;
    $net_total = $dom->find('table')->find('table')[7]->find('span')[6]->innerHtml;
    $extracted_data = array(
        'delivery_date' => $delivery_date,
        'delivery_name' => $delivery_name,
        'delivery_address' => $delivery_address,
        'item' => $item,
        'quantity' => $quantity,
        'unit_price' => $unit_price,
        'total' => $total,
        'shipping_cost' => $shipping,
        'net_total' => $net_total,

    );
    print_r($extracted_data);
} else {
    echo "Response Failed or Query Not Found";
}
