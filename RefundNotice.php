<?php
require 'vendor/autoload.php';
include_once('Curl_parser.php');

use PHPHtmlParser\Dom;

$url = 'file:///C:/Users/LC/Desktop/RefundNotice.html';

$curl_parser = new Curl_parser();
$parsed_html = $curl_parser->Fetch_URL($url);

//output, you can also save it locally on the server
$output = fopen("testfile.html", "w");
fwrite($output, $parsed_html);
fclose($output);
// dom object
$dom = new Dom();
// load Dom from file
$dom->loadFromFile('testfile.html');
// reading content from file where class name is setDisplayWidthInner
$contents = $dom->find('.setDisplayWidthInner');
//writing new file with new content
if(!empty($contents)){
    $extracted_div = fopen("extracted.html","w");
    fwrite($extracted_div, $contents->innerHtml);
    fclose($extracted_div);

    $dom->loadFromFile("extracted.html");
    $extracted_price = $dom->find('p')->innerHtml;
    $extracted_orderNo = $dom->find('span')[1]->innerHtml;
    $extracted_orderDate = $dom->find('span')[3]->innerHtml;
    $extracted_price = (float) filter_var($extracted_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $extracted_price = '$'.$extracted_price;
    $extracted_data = array(
        'price' => $extracted_price,
        'orderNo' => $extracted_orderNo,
        'orderDate' => $extracted_orderDate
    );
    print_r($extracted_data);
}else{
    echo "Response Failed or Query Not Found";
}
?>