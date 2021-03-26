<?php
if ($argc < 3) {
	echo "usage: php -f createPalette.php input output";
	exit(1);
}
$bit12 = $argc > 3 && $argv[3] == "-12bit";
if ($bit12) {echo "12bit mode on.";}
$output = "";
for ($i = 0; $i < ($bit12 ? 512 : 160); $i++) {$output .= chr(0);}
$input = file_get_contents($argv[1]);
$input = str_replace("\r","",$input);
$o_input = explode("\n",$input);
$input = array();
// var_dump($o_input);
for( $i = 0; $i < count($o_input); $i++) {
	$line = $o_input[$i];
	if (strlen($line) > 0 && $line[0] != ";") {
		$input[] = $line;
	}
}
// var_dump($input);
$offset = ($bit12 ? 0 : 1);
$maxOffset = ($bit12 ? 16 : 3);
for ($i = 0; $i < count($input); $i += $maxOffset) {
	for ($j = 0; $j < $maxOffset; $j++) {
		$color = str_replace("#","",$input[$i+$j]);
		if ($bit12) {
			$red = hexdec($color[0]);
			$green = hexdec($color[1]);
			$blue = hexdec($color[2]);
		} else {
			$red = min(16,round(hexdec(substr($color,0,2)) / 16));
			$green =  min(16,round(hexdec(substr($color,2,2)) / 16));
			$blue =  min(16,round(hexdec(substr($color,4,2)) / 16));
		}
		$output[$i/$maxOffset*16+$j+$offset] = chr($green * 16 + $blue);
		$output[$i/$maxOffset*16+$j+$offset+($bit12 ? 256 : 80)] = chr($red);

	}
}
file_put_contents($argv[2],$output);
?>
