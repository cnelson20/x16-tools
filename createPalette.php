<?php
if ($argc < 3) {
	echo "usage: php -f createPalette.php input output";
	exit(1);
}
$output = "";
for ($i = 0; $i < 512; $i++) {$output .= chr(0);}
$input = file_get_contents($argv[1]);
$input = str_replace("\r","",$input);
$input = explode("\n",$input);
for( $i = 0; $i < count($input); $i++) {
	$lines = $input[$i];
	if ($lines[0] == ";") {
		unset($input[$i]);
		$i--;
	}
}
$input = array_values($input);
for ($i = 0; $i < count($input); $i += 3) {
	for ($j = 0; $j < 3; $j++) {
		$color = str_replace("#","",$input[$i+$j]);
		$red = round(hexdec(substr($color,0,2)) / 16.5);
		$green = round(hexdec(substr($color,2,2)) / 16.5);
		$blue = round(hexdec(substr($color,4,2)) / 16.5);
		$output[$i/3*16+$j+1] = chr($green * 16 + $blue);
		$output[$i/3*16+$j+257] = chr($red);

	}
}
file_put_contents($argv[2],$output);
?>
