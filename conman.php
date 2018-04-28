<?php

	class ConMan {

        public function __construct() {

        	
        }

        public function addcon($con) {
        	$this->cons[] = [$con->ID => $con];
   		}

   		public function showcons() {

   			print_r($this->cons);

   		}

    }

?>