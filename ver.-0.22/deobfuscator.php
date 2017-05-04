<?php

/*

FOPO PHP Deobfuscator script

Description: Deobfuscator script for FOPO PHP obfuscated files
Author: Antelox
Version: 0.22
Date: 05/04/2017

Coded by Antelox
Twitter: @Antelox
UIC R.E. Academy - quequero.org

Copyright (C) 2017 - MIT License

*PHP script version*

Online version: https://glot.io/snippets/efruafhnez

This is the updated version after that FOPO PHP Obfuscator's author has updated
his obfuscation algorithm

If it doesn't work in the future, please send me an email at

anteloxrce(at)gmail(dot)com

and I'll will try to update the script quickly. Otherwise, fork it
and make appropriate changes. =)

*/

$contents = file_get_contents($argv[1]);
if (preg_match('/Obfuscation provided by FOPO - Free Online PHP Obfuscator:/',$contents) === 0) {
	echo "*ERROR: Provided a PHP script not obfuscated with FOPO PHP Obfuscator!";
	exit;
}

$contents = preg_replace('/\/\/?\s*\*[\s\S]*?\*\s*\/\/?/', '', $contents);
$eval = explode('(',$contents);

//$base64 = base64 encoded block inside obfuscated PHP script
$base64 = explode('"',$eval[2]);

$i1 = explode("eval",base64_decode($base64[1]));

//there is a ternary operator at this point "?:" -> (condition) ? (expr for TRUE) : (expr 4 FALSE)
//the right data block to be decoded is the second one, that is the data block relative to ":" (FALSE)
$i2 = explode(":",$i1[1]);
$i3 = explode("\"",$i2[1]); #$i3[1] = data block passed to decoding chain: gzinflate(base64_decode(str_rot13($i3[1])))

//Here final steps with n recursive encoded layers:
//First layer here
$encodedlayer = gzinflate(base64_decode(str_rot13($i3[1])));

//n-1 remaining layers inside while loop below
while (!preg_match('/\?\>/',$encodedlayer)) {
	$dl = explode("\"",$encodedlayer);
	if (sizeof($dl)>7) {
	    $nextlayer = gzinflate(base64_decode(str_rot13($dl[7])));
	    $encodedlayer = $nextlayer;
	}
	else {
	    $nextlayer = gzinflate(base64_decode($dl[5]));
	    $encodedlayer = $nextlayer;
	}

}

//here the deobfuscated PHP code it's printed :D

echo substr($encodedlayer, strpos($encodedlayer, '?>') + 2, strlen($encodedlayer));

?>