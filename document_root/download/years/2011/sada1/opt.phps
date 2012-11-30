<?php
/**
 * Computation of a really sophisticated function.
 * Programmed by the Interlos organizing team.
 * All rights reserved.
 */
fscanf(STDIN, "%d %d %d\n", &$A, &$B, &$C);
echo f($A, $B, $C) . "\n";


/**
 * @param int $A
 * @param int $B
 * @param int $C
 * @return int
 */
function f($A, $B, $C)
{
	$s = 0;
	for ($i = 0; $i < $C; $i++) {
		$s = ($s + $A * g($B, $i, h($i))) % h($i+1);
	}
	return $s % h($C);
}


/**
 * @param int $a
 * @param int $b
 * @param int $c
 * @return int
 */
function g($a, $b, $c)
{
	$r = 1;
	for ($i = 0; $i < $b; $i++) {
		$r = ($r * $a) % $c;
	}
	return $r;
}


/**
 * @param int $i
 * @return int
 */
function h($i)
{
	$p = array();
	$p[1] = 2;
	$pc = 1;
	do {
		$n = $p[$pc];
		$ok = false;
		do {
			$thisOk = true;
			$n++;
			for ($j = 1; $j <= $pc; $j++) {
				if ($n % $p[$j] == 0) $thisOk = false;
			}
			if ($thisOk) $ok = true;
		} while (!$ok);

		$pc++;
		$p[$pc] = $n;
	} while ($pc < 9999);
	return $p[ m($i) ];
}


/**
 * @param int $i
 * @return int
 */
function m($i)
{
	$k = 6543 ^ $i;
	for ($n = 0; $n < (3456 & $i); $n++) {
		for ($j = 0; $j < $i; $j++) {
			$k = $k ^ $j;
		}
	}
	return $i ^ $k;
}
