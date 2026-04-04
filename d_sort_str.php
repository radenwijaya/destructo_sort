<?php
	error_reporting(E_ALL); ini_set("display_errors", 1);

	$bitshift = [];
	$magnitude_bitshift = [];
	
	define('numbers', [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]);
	define('orders', ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j','k', 'l', 'm', 
		'n', 'o', 'p', 'q', 'r', 's', 't', /*'u', 'v', 'w', 'x', 'y', 'z'*/]);
	define('f_orders', array_flip(orders));
	define('order_count', count(orders));

	function bitshift_generator($bs_array, $depth, $value, $orders, $order_count, &$bitshift)
	/*
	 * This will generate bitshift, but not rergular bitshift
	 * Not regular bitshift.  
	 * It start from 0, and data value at zero is either zero or 1
	 * 
	 * it goes to 9, as this work on base 10 
	 * 
	 */
	{		
		for ($i=0; $i<=1; $i++)
		{
			if ($i==1)
			{
				$bs_array[$orders[$depth]]=$orders[$depth];
				$value=$value+2**$depth;
			}
			
			if ($depth<$order_count-1)
				bitshift_generator($bs_array, $depth+1, $value, $orders, $order_count, $bitshift);
			else
				if ($depth==$order_count-1)
				{
					$bitshift[$value]=$bs_array;
				}			
		}
	}
	
	function input_maker($n=10, $m=4)
	{
		$result=[];
		for ($j=0; $j<$n; $j++)
		{
			$s='';
			for ($i=0; $i<$m; $i++)
				$s=$s.orders[rand(0, order_count-1)];
			
			$result[]=$s;
		}
		
		return $result;
	}
	
	$bs_array=[];
	$time=microtime(true);
	
	bitshift_generator($bs_array, 0, 0, orders, order_count, $bitshift);	
	echo('Bitshift Generator');
	echo("<BR>Entropy: ".count($bitshift));
	echo(' Time: '.microtime(true) - $time);

	bitshift_generator($bs_array, 0, 0, numbers, count(numbers), $magnitude_bitshift);	
	
	/*
	 * No need to sort, actually
	 */
//	ksort($bitshift);
	
	function digit_storage($x, &$storage)
	/*
	 * This will store digit 0 to 9 into digit storage array
	 * $x must be within 0..9
	 * 
	 * $torage is an array, where
	 */
	{		
		if (isset($storage[$x]))		
			$storage[$x]++;
		else
		{
			$storage[$x]=1;
			
			if (!isset($storage[order_count]))
				$storage[order_count]=0;
			
			$storage[order_count]=$storage[order_count]+2**f_orders[$x];
		}
	}
			
	function structure_storage($value, &$storage, $depth)
	/*
	 * This will initialise the general stoarge
	 */
	{		
		if (!isset($storage[$value]))
		{
			$storage[$value]=[];
			
			if (!isset($storage[order_count]))
				$storage[order_count]=0;
			
			$storage[order_count]=$storage[order_count]+2**f_orders[$value];			
		}				
	}
	
	function store_recursive($digits, $depth, $cnt, &$storage)
	/*
	 * Store and prepare the data structure
	 */
	{		
		if ($depth<$cnt-1)
		{
			structure_storage($digits[$depth], $storage, $depth+1);
			
			store_recursive($digits, $depth+1, $cnt, $storage[$digits[$depth]]);
		}
		else
		{
			digit_storage($digits[$depth], $storage);			
		}
	}
	
	function store($digits, &$structure)
	/*
	 * This function will number in storage.  In theory, any number
	 * 
	 * Non recurrsive
	 */
	{
//		$digits=''.$x;
		$cnt=strlen($digits);
					
		if (!isset($structure[$cnt]))
			$structure[0]=$structure[0]+2**($cnt);
		
		store_recursive($digits, 0, $cnt, $structure[$cnt]);	
	}
	
	function digit_output($storage)
	/*
	 * This will check storage[10] and use bitshift to output sorted result
	 *
	 * best used with yield method
	 */
	{
		global $bitshift;
		
		foreach ($bitshift[$storage[order_count]] as $value)
		{			
			for ($i=0; $i<$storage[$value]; $i++)
				yield $value;
		}
	}
	
	function output_recursive($storage, $v, $depth, &$output)
	{
		global $bitshift;
		
		if ($depth>1)	
		{
			foreach ($bitshift[$storage[order_count]] as $value)
			{	
				output_recursive($storage[$value], $v.$value, $depth-1, $output);				
			}
		}
		else
		{
			foreach (digit_output($storage) as $value)
			{				
				$output[]=$v.$value;
			}
		}
	}
	
	function d_sort(&$input)
	/*
	 * Destructive Sort entry point
	 */
	{
		global $magnitude_bitshift;
		
		$structure=[0 => 0];
		$result=[];
		
		foreach ($input as $value)
			store($value, $structure);
		
		foreach ($magnitude_bitshift[$structure[0]] as $value)
			output_recursive($structure[$value], '', $value, $result);
			
		return $result;
	}
		
	$input=[];
	
/*	$n=100000;
	$m=100;
	for ($i=0; $i<$n; $i++)
		$input[]=''.rand(0, $m);*/
	$m=4;
	$n=100000;
	$input=input_maker($n, $m);
				
	echo("<BR>N: ".$n." M: ".$m);
	
	$time=microtime(true);
	$sorted=d_sort($input);
	echo('<BR>Destructive Sort ');
	echo('Time: '.microtime(true) - $time);
		
	$php_sort=$input;
	
	$time=microtime(true);
	sort($php_sort);
	echo("<BR>PHP Sort ");
	echo('Time: '.(microtime(true) - $time));
	
	function quickSortOptimized(array &$array, int $low, int $high): void
	//https://zetcode.com/php/quick-sort/
	{
		if ($low < $high) {
			$pi = partition($array, $low, $high);
			
			quickSortOptimized($array, $low, $pi - 1);
			quickSortOptimized($array, $pi + 1, $high);
		}
	}
	
	function partition(array &$array, int $low, int $high): int {
		$pivot = $array[$high];
		$i = $low - 1;
		
		for ($j = $low; $j <= $high - 1; $j++) {
			if ($array[$j] < $pivot) {
				$i++;
				[$array[$i], $array[$j]] = [$array[$j], $array[$i]];
			}
		}
		
		[$array[$i + 1], $array[$high]] = [$array[$high], $array[$i + 1]];
		return $i + 1;
	}
	
	$time=microtime(true);
	$quicksort=$input;	
	quickSortOptimized($quicksort, 0, count($input) - 1);
	
	echo("<BR>QuickSort ");
	echo('Time: '.(microtime(true) - $time));
	/*
	function radixSort(array $arr): array
	//https://zetcode.com/php/radix-sort/
	{
		$maxDigits = max(array_map('strlen', array_map('strval', $arr)));
		
		for ($digit = 0; $digit < $maxDigits; $digit++) {
			$buckets = array_fill(0, 10, []);
			
			foreach ($arr as $num) {
				$digitVal = (int) (($num / (10 ** $digit)) % 10);
				$buckets[$digitVal][] = $num;
			}
			
			$arr = array_merge(...$buckets);
		}
		
		return $arr;
	}

	$time=microtime(true);
	$radixsort=$input;
	radixSort($radixsort);
	echo("<BR>Radixsort ");
	echo('Time: '.microtime(true) - $time);
*/		
	/*
	 * Proofing Ground
	 */
	
	 if ($sorted===$php_sort)
	 {
		echo("<BR>Sort OK<BR>");
	 	for ($i=0; $i<10; $i++)
		{
			$rand_check=rand(0, $n-1);
	 
			if ($sorted[$rand_check]==$php_sort[$rand_check])
			{
	 			echo("Element ".$rand_check." OK!<BR>");
	 		}
	 		else
	 		{	 		
	 			echo("Element ".$rand_check." Mismatch!<BR>");
	 		}
	 	}
	 }	 	
?>