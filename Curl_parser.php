<?php
    class Curl_parser{

        public function Fetch_URL($url){
            $ch=curl_init();
            $timeout=5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

            // Get URL content
            $lines_string=curl_exec($ch);
            // close handle to release resources
            curl_close($ch);
            return $lines_string;
        } 

    }
?>