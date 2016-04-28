<?php

/*

FOPO PHP Deobfuscator script

Description: Deobfuscator script for FOPO PHP obfuscated files
Author: Antelox
Version: 0.1
Date: 04/28/2016

Coded by Antelox
Twitter: @Antelox
UIC R.E. Academy - quequero.org

"Use it, change it, share it" License

*PHP script version*

Online version: https://glot.io/snippets/ee5mzg3zf1

This code works fine for the last version of FOPO PHP Obfuscator.
Since no version number is present on the FOPO Homepage, I cannot
report it here. Test date: 04/28/2016

For older versions you can take a look here:

- https://github.com/r3dsm0k3/FOPO-PHP-De-Obfuscator (v 1.0 from which I got the idea)
- http://lombokcyber.com/en/detools/decode-fopo (v. 1.2)

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

$eval = explode('(',$contents);

//$base64 = base64 encoded block inside obfuscated PHP script
$base64 = explode('"',$eval[3]);

$i1 = explode("eval",base64_decode($base64[1]));

//there is a ternary operator at this point "?:" -> (condition) ? (expr for TRUE) : (expr 4 FALSE)
//the right data block to be decoded is the second one, that is the data block relative to ":" (FALSE)
$i2 = explode(":",$i1[1]);
$i3 = explode("\"",$i2[1]); #$i3[1] = data block passed to decoding chain: gzinflate(base64_decode(str_rot13($i3[1])))

//Here final steps with n recursive encoded layers: gzinflate(base64_decode(str_rot13(datablock)))
//First layer here
$encodedlayer = gzinflate(base64_decode(str_rot13($i3[1])));

//n-1 remaining layers inside while loop below
while (!preg_match('/\/\/\$[a-z0-9]{8}\=\"/',$encodedlayer)) {
	$dl = explode("(\"",$encodedlayer);
	$nextlayer = gzinflate(base64_decode(str_rot13($dl[1])));
	$encodedlayer = $nextlayer;
}

//here $final[1] variable contains deobfuscated PHP code :D
$final = explode("?>",$encodedlayer);
echo $final[1];

?>