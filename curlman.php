<?php

class CurlMan {

      public function __construct() {

      }

	public static function curlrequest($request_path='', $httpHeadArr=[]) {

      	$con = curl_init($request_path);                                                                                                                                      
            curl_setopt($con, CURLOPT_HTTPHEADER, $httpHeadArr);                                                                 
            curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);   
            curl_setopt($con, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');                                                                   

            $response = curl_exec($con);

            ////debug 
            // echo $request_path;
            // print_r($httpHeadArr);
            // echo curl_error($con);

            return $response;
      }
}

?>