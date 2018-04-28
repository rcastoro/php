<!doctype html>

<?php
	

	//POST Details
	$selected_action = isset($_POST['action']) ? $_POST['action'] :'/accounts';
	$selected_product = isset($_POST['product']) ? $_POST['product'] : 'Choose One';
	$selected_quantity = isset($_POST['quantity']) ? $_POST['quantity'] :'0';

	//// CONNECTIONS

	// //MySQL
	// require_once 'mysqli.php';
	// Enabled mysql variable connection $mysqli;

    //Global
	require_once 'conman.php';
	require_once 'productticker.php';
	$conman = new ConMan();
	
	// Gdax
	require_once 'gdax_api.php';

	// GET BTC QUEEN BEE; PRICE IN SATOSHIS ARE WHAT ALL COINS ARE TRADED AGAINST

	$key = // USE GDAX KEY HERE;
    $secret = // USE GDAX SECRET HERE;
    $passphrase = // USE GDAX PASSPHRASE HERE;
    $time = time();

	$con = new GDaxExchange($key, $secret, $passphrase, $time);

	// Setup connection to exchange
	$conman->addcon($con);
	$conman->addcon($con);
	$con = null;

	$conman->cons[0]['gdax']->signmessage($conman->cons[0]['gdax'], '/products/BTC-USD/ticker' , 'GET');
	$responseBTC = json_decode($conman->cons[0]['gdax']->curlrequest(), true);
	$ptBTC = new ProductTicker($responseBTC);

	$conman->cons[1]['gdax']->signmessage($conman->cons[1]['gdax'], '/products/ETH-USD/ticker' , 'GET');
	$responseETH = json_decode($conman->cons[1]['gdax']->curlrequest(), true);
	$ptETH = new ProductTicker($responseETH);

   	// Binance
	require_once 'binance_api.php';

	$con = new BinanceExchange();
	$conman->addcon($con);
	$con=null;

	if(isset($_POST['product']))
	{
		$symbol = '?symbol='.$_POST['product'];
	}
	else
	{
		$symbol = '';
	}

	$priceResponseBTC = json_decode($conman->cons[2]['binance']->curlrequest('/ticker/price'.$symbol.'BTC'), true);
	$priceResponseETH = json_decode($conman->cons[2]['binance']->curlrequest('/ticker/price'.$symbol.'ETH'), true);

	$products = json_decode($conman->cons[2]['binance']->curlrequest('/ticker/price'), true);

	foreach($products as $product)
	{
		if(substr($product['symbol'], -3, 3) == "ETH" OR 
			substr($product['symbol'], -3, 3) == 'BTC' )
		{
			$ProductIDs[] = substr($product['symbol'], 0, -3);
		}
	}

	$priceResponseAll = json_decode($conman->cons[2]['binance']->curlrequest('/ticker/price'), true);
	$btcPair = [];
	$ethPair = [];

	foreach($priceResponseAll as $pair)
	{
		if(substr($pair['symbol'], -3, 3) == "BTC")
		{
			if(substr($pair['symbol'], 0, 3) != "ETH")
			{
				array_push($btcPair, $pair);
			}
		}
		else if(substr($pair['symbol'], -3, 3) == "ETH")
		{
			array_push($ethPair, $pair) ;
		}
	}

	//// SQL TEST

	// $result = $mysqli->query("SELECT FirstName FROM tbl_Users");

	// $row = $result->fetch_assoc();
	// echo $row['FirstName'];

	// For finding highest alt coin conversion for BTC/ETH

	// $btcCashArr = [];
	// $ethCashArr = [];

	// for($x = 0; $x<count($btcPair); $x++)
	// {
	// 	$arr = [$btcPair[$x]['symbol'] => ($ptBTC->price * $btcPair[$x]['price'])];
	// 	array_push($btcCashArr, $arr);
	// }

	// for($x = 0; $x<count($ethPair); $x++)
	// {
	// 	$arr = [$ethPair[$x]['symbol'] => ($ptETH->price * $ethPair[$x]['price'])];
	// 	array_push($ethCashArr, $arr);	
	// }

	// $HighestBTCSale = 0.00;
	// $HighestBTCSymbol = '';
	// $usdCashOutPrice = 0.00;

	// foreach($btcCashArr as $pair)
	// {
	// 	foreach($pair as $name => $value)
	// 	{
	// 		$usdCashOutPrice = $value - $value;
	// 		if($usdCashOutPrice > $HighestBTCSale)
	// 		{
	// 			$HighestBTCSymbol = $name;
	// 			$HighestBTCSale = $usdCashOutPriceBTC;
	// 		}
	// 	}
	// }

	// echo $HighestBTCSale." on ".$HighestBTCSymbol;

	krsort($products);

?>

<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="../css/style.css">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->

	
	<title>Wazaaaaa World!</title>
</head>
<body>
	<div class="container" id="bg-blue">
		Crypto-Convert To USD
	</div>
	<div class="container" id="bg-lightblue">
		<form action="index.php" method="post">
		
			<!-- DISPLAY BTC PRICE -->
			<br/>
			<h3>BTC USD: $<?php echo $ptBTC->price; ?></h3>
			<h3>ETH USD: $<?php echo $ptETH->price; ?></h3>
			<!-- Convert to USD -->
			<br/>
			Symbol: 
			<select name="product">
	   			<option selected="selected"><?php echo $selected_product; ?></option>
				<?php
				foreach($ProductIDs as $product)
				{?>
					<option value="<?php echo $product; ?>"><?php echo $product; ?></option>
				<?php 
				}
				?>
			</select> 
			<br/>
			<br/>
			Quantity: 
			<input type="text" name="quantity">
			<br/>
			<br/>
			<input type="submit" name = "submit">
			<input type = "hidden" name="submitted" value = "TRUE">
			<!-- Action: 
			<select name="action">
	   			<option selected="selected">Choose one</option>
				<?php
				foreach($QueryTypes as $key => $value)
				{?>
					<option value="<?php echo $value; ?>"><?php echo $key; ?></option>
				<?php 
				}
				?>
			</select>

			Symbol: 
			<select name="product">
	   			<option selected="selected">Choose one</option>
				<?php
				foreach($ProductIDs as $product)
				{?>
					<option value="<?php echo $product; ?>"><?php echo $product; ?></option>
				<?php 
				}
				?>
			</select>
			<br/>Qty: <input type="text" name="quantity"><br>
			<input type="submit" name = "submit">
			<input type = "hidden" name="submitted" value = "TRUE"> -->
		</form>
	</div>
	<div class="container" id="bg-lightblue">
		<?php
			$ptBinanceBTC = new ProductTicker($priceResponseBTC);
			$ptBinanceETH = new ProductTicker($priceResponseETH);
		?>
		<h1><?php echo '<font color="red" size="6px">'. $selected_quantity .'</font> '.$selected_product . ' ( ' . $ptBinanceBTC->price * 100 . '% BTC) '  ; ?> </h1>
		<table>
			<tr>
				<td width="50px"><h4>Symbol</h4></td>
				<td width="200px"><h4>Satoshis</h4></td>
				<td width="200px"><h4>USD Price</h4></td>
				<td width="200px"><h4>Sell Off Price USD (CoinBase)</h4></td>
			</tr>
			<tr>
				<td width="200px"><font color="green">BTC</font></td>
				<td width="200px"><?php echo $ptBinanceBTC->price; ?></td>
				<td width="200px">$<?php echo $ptBTC->price * $ptBinanceBTC->price; ?></td>
				<td width="200px">$<?php echo ($ptBTC->price * $ptBinanceBTC->price) * $selected_quantity; ?></td>
			</tr>
			<tr>
				<td width="200px"><font color="green">ETH</font></td>
				<td width="200px"><?php echo $ptBinanceETH->price; ?></td>
				<td width="200px">$<?php echo $ptETH->price * $ptBinanceETH->price; ?></td>
				<td width="200px">$<?php echo ($ptETH->price * $ptBinanceETH->price) * $selected_quantity; ?></td>
			</tr>
		</table>
		<!-- Show coin with highest return when sold to BTC/ETH -->
		<table>
			<tr>
				<td width="50px"><h4>Symbol</h4></td>
				<td width="200px"><h4>Satoshis</h4></td>
				<td width="200px"><h4>USD Price</h4></td>
				<td width="200px"><h4>Sell Off Price USD (CoinBase)</h4></td>
			</tr>
			<tr>
				<td width="200px"><font color="green">BTC</font></td>
				<td width="200px"><?php echo $ptBinanceBTC->price; ?></td>
				<td width="200px">$<?php echo $ptBTC->price * $ptBinanceBTC->price; ?></td>
				<td width="200px">$<?php echo ($ptBTC->price * $ptBinanceBTC->price) * $selected_quantity; ?></td>
			</tr>
			<tr>
				<td width="200px"><font color="green">ETH</font></td>
				<td width="200px"><?php echo $ptBinanceETH->price; ?></td>
				<td width="200px">$<?php echo $ptETH->price * $ptBinanceETH->price; ?></td>
				<td width="200px">$<?php echo ($ptETH->price * $ptBinanceETH->price) * $selected_quantity; ?></td>
			</tr>
		</table>
		<div>.</div>
	</div>
</body>
</html>