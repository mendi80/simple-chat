<?php


$N=1e6;

$t0=microtime(true);

$s=0;
for ($i=0; $i<$N ; $i++) 
	$s+=$i;
	
$t1=microtime(true);

$dt=$t1-$t0;
$NM = $N/1e6;
echo "dt=$dt, N={$NM}M";
