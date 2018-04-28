<?php

	require_once('exchange.php');

	class BinanceExchange extends exchange {

		public $ID = "binance";

		public function __construct() {
			$this->url = 'https://api.binance.com/api/v1';
			$this->httpHeadArr = array();
		}

		 public function curlrequest($path) {
		    return CurlMan::curlrequest($this->url.$path, $this->httpHeadArr);
		 }

		 public function comparesymbols($sym1, $sym2) {
		 			    return CurlMan::curlrequest($this->url.$path, $this->httpHeadArr);

		 }
		// debug 
		// $err_status = curl_error($ch);
		// echo $err_status;
	}

?>