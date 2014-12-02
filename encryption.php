<?php

// Encrytion

//Lånt kode fra Mikkel Frid (XB-05) og Philip Barkow (XA-10)

function encrypt_str($string)
{
	$to_enc =  $string;

	$xor_key= 17;
	$the_res="";
	for($i=0;$i<strlen($to_enc);++$i)
	{
		$the_res.=chr($xor_key^ord($to_enc[$i]));	
	}
	return$the_res;
}

// xor_str();

// Decryption
function decrypt_str($string)
{
	$to_dec=$string;

	$xor_key= 17;
	$dec_res="";
	for($i=0;$i<strlen($to_dec);$i++)
	{
		$dec_res.=chr($xor_key^ord($to_dec[$i]));
	}
	return$dec_res;
}

// decrypt_str();
?>