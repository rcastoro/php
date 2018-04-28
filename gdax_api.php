<?php
    require_once('exchange.php');

    class GDaxExchange extends exchange {

        public $ID = "gdax";
        public $url = "https://api.gdax.com";

        public function __construct($key, $secret, $passphrase, $time) {
            $this->key = $key;
            $this->secret = $secret;
            $this->passphrase = $passphrase;
            $this->time = $time;
            $this->b64 = '';
            $this->path = '';
            $this->httpHeadArr = [];
        }

        public function signature($request_path='', $body='', $timestamp=false, $method='GET') {
            $body = is_array($body) ? json_encode($body) : $body;
            $timestamp = $timestamp ? $timestamp : $this->time;

            $what = $timestamp.$method.$request_path.$body;

            return base64_encode(hash_hmac('sha256', $what, base64_decode($this->secret), true));
        }

        public function signmessage($con, $path='', $method='GET') {
            
            $this->b64 = $con->signature($path, '', false, $method);
            $this->path = $path;

            $this->httpHeadArr = ['CB-ACCESS-KEY: ' . $this->key, 
                        'CB-ACCESS-SIGN: ' . $this->b64, 
                        'CB-ACCESS-TIMESTAMP: ' . $this->time, 
                        'CB-ACCESS-PASSPHRASE: ' . $this->passphrase,
                        'Content-Type: application/json'
                        ]; 
        }

        public function curlrequest() {
            return CurlMan::curlrequest($this->url.$this->path, $this->httpHeadArr);
        }

        public function debugmessage() {
            echo $this->key;
            echo $this->b64;
        }
    }
?>