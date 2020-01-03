<?php
require 'vendor/autoload.php';
include_once('Curl_parser.php');

use PHPHtmlParser\Dom;

$url = 'file:///C:/Users/LC/Desktop/OrderDelivery.html';

$curl_parser = new Curl_parser();
$parsed_html = $curl_parser->Fetch_URL($url);

//output, you can also save it locally on the server
$output = fopen("testfile.html", "w");
fwrite($output, $parsed_html);
fclose($output);
// dom object
$dom = new Dom();
// load Dom from file
$dom->loadFromFile("testfile.html");
// reading content from file where class name is setDisplayWidthInner
$contents = $dom->find('.setDisplayWidthInner')[3];
//writing new file with new content
if (!empty($contents)) {
    $extracted_div = fopen("extracted.html", "w");
    fwrite($extracted_div, $contents->innerHtml);
    fclose($extracted_div);

    $dom->loadFromFile("extracted.html");
    $extracted_product = $dom->find('span')[3]->innerHtml;
    $extracted_price = $dom->find('span')[2]->innerHtml;
    $extracted_quantity = $dom->find('span')[4]->innerHtml;
    $extracted_total = $dom->find('span')[5]->innerHtml;
    $extracted_data = array(
        'product' => $extracted_product,
        'unitprice' => $extracted_price,
        'quantity' => $extracted_quantity,
        'total' => $extracted_total
    );
    print_r($extracted_data);
} else {
    echo "Response Failed or Query Not Found";
}
