<?php
    class Curl_parser{

        public function Fetch_URL($url){
            $ch=curl_init();
            $timeout=5;
            // Getting contents from URL
            curl_setopt($ch, CURLOPT_URL, $url);
            // Setting transfer to true
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // Setting Timeout
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            // Get URL content
            $lines_string=curl_exec($ch);
            curl_close($ch);
            // returning output
            return $lines_string;
        } 

    }
?>