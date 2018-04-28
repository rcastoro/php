<?php

	class ProductTicker {

		public function __construct($ptArr) {

			$this->trade_id = '';
			$this->price = '';
			$this->size = '';
			$this->bid = '';
			$this->ask = '';
			$this->volume = '';
			$this->time = '';

			if(!empty($ptArr))
			{
				if(array_key_exists('trade_id', $ptArr))
				{
					$this->trade_id = $ptArr['trade_id'];
				}
				if(array_key_exists('price', $ptArr))
				{
					$this->price = $ptArr['price'];
				}
				if(array_key_exists('volume', $ptArr))
				{
					$this->volume = $ptArr['volume'];
				}
			}
		}
	}
?>