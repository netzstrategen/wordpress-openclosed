<?php 

function oc_convertToAscii($string) {
	$toReturn = $string;
	$toReturn = strtolower(iconv("utf-8","ascii//TRANSLIT",str_replace(' ', '', $toReturn)));

	$toReturn = str_replace("'",'', $toReturn);
	$toReturn = str_replace('"','', $toReturn);
	$toReturn = str_replace('^','', $toReturn);
	$toReturn = str_replace('°','', $toReturn);
	$toReturn = str_replace('´','', $toReturn);    	
	$toReturn = str_replace('`','', $toReturn);    	
	$toReturn = str_replace('-','', $toReturn);    	
	$toReturn = str_replace('_','', $toReturn);
	$toReturn = str_replace('/','', $toReturn);
	$toReturn = str_replace('\\','', $toReturn);
	$toReturn = str_replace('&','', $toReturn);
	$toReturn = str_replace('?','', $toReturn);
	$toReturn = str_replace('%','', $toReturn);
	$toReturn = str_replace('$','', $toReturn);

	return $toReturn;
}



function oc_convertUmlaute($string) {
	$toReturn = $string;

	$toReturn = str_ireplace('Ä', 'Ae',$toReturn);
	$toReturn = str_ireplace('Á', 'A',$toReturn);
	$toReturn = str_ireplace('À', 'A',$toReturn);
	$toReturn = str_ireplace('Â', 'A',$toReturn);
	$toReturn = str_ireplace('Æ', 'Ae',$toReturn);
	$toReturn = str_ireplace('Ç', 'C',$toReturn);
	$toReturn = str_ireplace('É', 'E',$toReturn);
	$toReturn = str_ireplace('È', 'E',$toReturn);
	$toReturn = str_ireplace('Ê', 'E',$toReturn);
	$toReturn = str_ireplace('Ë', 'E',$toReturn);
	$toReturn = str_ireplace('Í', 'I',$toReturn);
	$toReturn = str_ireplace('Ì', 'I',$toReturn);
	$toReturn = str_ireplace('Î', 'I',$toReturn);
	$toReturn = str_ireplace('Ï', 'I',$toReturn);
	$toReturn = str_ireplace('Ö', 'Oe',$toReturn);
	$toReturn = str_ireplace('Ó', 'O',$toReturn);
	$toReturn = str_ireplace('Ò', 'O',$toReturn);
	$toReturn = str_ireplace('Ô', 'O',$toReturn);
	$toReturn = str_ireplace('Œ', 'Oe',$toReturn);
	$toReturn = str_ireplace('Ü', 'Ue',$toReturn);
	$toReturn = str_ireplace('Ú', 'U',$toReturn);
	$toReturn = str_ireplace('Ù', 'U',$toReturn);
	$toReturn = str_ireplace('Û', 'U',$toReturn);
	$toReturn = str_ireplace('Ÿ', 'Y',$toReturn);
	$toReturn = str_ireplace('ä', 'ae',$toReturn);
	$toReturn = str_ireplace('á', 'a',$toReturn);
	$toReturn = str_ireplace('à', 'a',$toReturn);
	$toReturn = str_ireplace('â', 'a',$toReturn);
	$toReturn = str_ireplace('æ', 'ae',$toReturn);
	$toReturn = str_ireplace('ç', 'c',$toReturn);
	$toReturn = str_ireplace('é', 'e',$toReturn);
	$toReturn = str_ireplace('è', 'e',$toReturn);
	$toReturn = str_ireplace('ê', 'e',$toReturn);
	$toReturn = str_ireplace('ë', 'e',$toReturn);
	$toReturn = str_ireplace('í', 'i',$toReturn);
	$toReturn = str_ireplace('ì', 'i',$toReturn);
	$toReturn = str_ireplace('î', 'i',$toReturn);
	$toReturn = str_ireplace('ï', 'i',$toReturn);
	$toReturn = str_ireplace('ö', 'oe',$toReturn);
	$toReturn = str_ireplace('ó', 'o',$toReturn);
	$toReturn = str_ireplace('ò', 'o',$toReturn);
	$toReturn = str_ireplace('ô', 'o',$toReturn);
	$toReturn = str_ireplace('œ', 'oe',$toReturn);
	$toReturn = str_ireplace('ß', 'ss',$toReturn);
	$toReturn = str_ireplace('ü', 'ue',$toReturn);
	$toReturn = str_ireplace('ú', 'u',$toReturn);
	$toReturn = str_ireplace('ù', 'u',$toReturn);
	$toReturn = str_ireplace('û', 'u',$toReturn);
	$toReturn = str_ireplace('ÿ', 'y',$toReturn);

	return $toReturn;
}
