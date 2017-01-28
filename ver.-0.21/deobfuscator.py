#!/usr/bin/env python

__description__ = 'Deobfuscator script for FOPO PHP obfuscated files'
__author__ = 'Antelox'
__version__ = '0.21'
__date__ = '01/28/2017'

"""

FOPO PHP Deobfuscator script

Coded by Antelox
Twitter: @Antelox
UIC R.E. Academy - quequero.org

Copyright (C) 2017 - MIT License

*Python script version*

Online version: https://glot.io/snippets/efruafhnez

This is the updated version after that FOPO PHP Obfuscator's author has updated
his obfuscation algorithm

If it doesn't work in the future, please send me an email at

anteloxrce(at)gmail(dot)com

and I'll will try to update the script quickly. Otherwise, fork it
and make appropriate changes. =)

"""

import zlib
import base64
import sys
import re

def str_rot13(string):
    return string.encode('rot13')
	
def base64_decode(string):
    return base64.b64decode(string)

def gzinflate(string):
    return zlib.decompress(string, -15)

#main
if len(sys.argv) > 1:
	print "\n***FOPO Deobfuscator ver. 0.2***\n"
	
	contents = open(sys.argv[1],'r').read()
	if "Obfuscation provided by FOPO - Free Online PHP Obfuscator:" not in contents:
		print "*ERROR: Provided a PHP script not obfuscated with FOPO PHP Obfuscator!"
		sys.exit()

	contents = re.sub('//?\s*\*[\s\S]*?\*\s*//?', '', contents)
	
	eval = contents.split('(')
	
	#base64 = base64 encoded block inside obfuscated PHP script
	base64_ = eval[2].split('"')
	
	i1 = base64_decode(base64_[1]).split("eval")
	
	#there is a ternary operator at this point "?:" -> (condition) ? (expr for TRUE) : (expr 4 FALSE)
	#the right data block to be decoded is the second one, that is the data block relative to ":" (FALSE)
	i2 = i1[1].split(':')
	i3 = i2[1].split('"')
	
	#initialization variables
	encryptionlayer = ''
	dl = ''
	nextlayer = ''
	backup = ''
	#Here final steps with n recursive encoded layers:
	#First layer here
	encryptionlayer = gzinflate(base64_decode(str_rot13(i3[1])))

	#n-1 remaining layers inside while loop below
	while (str(re.match('\?\>', encryptionlayer)) == 'None'):
		backup = encryptionlayer
		dl = encryptionlayer.split('"')

		if (len(dl)>7):
			nextlayer = gzinflate(base64_decode(str_rot13(dl[7])))
			encryptionlayer = nextlayer
		else:
			nextlayer = gzinflate(base64_decode(dl[5]))
			encryptionlayer = nextlayer

	#here final[1] variable contains deobfuscated PHP code :D
	backup = encryptionlayer
	final = backup.split('?>')

	try:
		open(sys.argv[2],'wb').write(final[1])
	except:
		open('deobfuscated.php','wb').write(final[1])

else:
	print "\n*ERROR: Please provide the input file name as argument!"
	print "\nExample: python deobfuscator.py input.php [output=deobfuscated.php]"
	sys.exit()
