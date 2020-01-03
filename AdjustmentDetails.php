<?php
require "vendor/autoload.php";
include_once('Curl_parser.php');

use PHPHtmlParser\Dom;
// use Curl_parser; 
$url = 'file:///C:/Users/LC/Desktop/files_previous.html';

$curl_parser = new Curl_parser();
$parsed_html = $curl_parser->Fetch_URL($url);

//output, you can also save it locally on the server
$output = fopen("testfile.html", "w");
fwrite($output, $parsed_html);
fclose($output);
// dom object
$dom = new Dom;
// load Dom from file
$dom->loadFromFile('testfile.html');
// reading content from file where class name is MsoNormalTable
$contents = $dom->find('.MsoNormalTable')[2];
if (!empty($contents)) {
	foreach ($contents as $content) {
		//writing new file with new content
		$extracted_div = fopen("extracted.html", "w");
		fwrite($extracted_div, $content->firstChild()->innerHtml);
		fclose($extracted_div);
	}
	$dom->loadFromFile('extracted.html');
	$extracted_product = $dom->find('span')[4];
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
