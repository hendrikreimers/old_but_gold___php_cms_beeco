<?

function picsImg_resizeReplace($output) {

	// Callback Funktion
	function imgReplace_callback ($matches) {

		foreach ( $matches as $key => $val ) {
			if ( preg_match('@^src="(.*init=loadimg.*[^"]|.*/p/(big|small)[0-9]{1,}.jpg)"$@i',$val,$match) ) {
				$src     = $match[1];
				$src_key = $key;
			} elseif ( preg_match('/^width="(.*[^"])"$/i',$val,$match) ) {
				$width     = (int)$match[1];
				$width_key = $key;
			} elseif ( preg_match('/^height="(.*[^"])"$/i',$val,$match) ) {
				$height     = (int)$match[1];
				$height_key = $key;
			}
		}

		if ( (strlen($src) > 0) && ($width > 0) && ($height > 0) ) {
			if ( preg_match('@^.*init=loadimg.*$@i',$src) ) {
				$newSrc = $src."&width=".$width."&height=".$height;
			} else $newSrc = preg_replace('=(.*/p/)(big|small)([0-9]{1,})(\.jpg)=i','$1$2$3_'.$width.'x'.$height.'$4',$src);
	
			$matches[$src_key] = 'src="'.$newSrc.'"';

			return $matches[1].$matches[2].$matches[3].$matches[4].$matches[5].$matches[6].$matches[7].$matches[8];
			
		} else return $matches[0];
	}
	
	// Regulärer Ausdruck um alle Bilder und Bilddaten zu finden
	$pattern = '@(<img)(.*)(src=".*[^"]"|width=".*[^"]"|height=".*[^"]")(.*)(src=".*[^"]"|width=".*[^"]"|height=".*[^"]")(.*)(src=".*[^"]"|width=".*[^"]"|height=".*[^"]")(.*[^>]>)@iU';
	#$pattern = '@(<img)(.*[^>])(src=".*[^"]"|width="[0-9]{1,}"|height="[0-9]{1,}")(.*[^>])(src=".*[^"]"|width="[0-9]{1,}"|height="[0-9]{1,}")(.*)(src=".*[^"]"|width="[0-9]{1,}"|height="[0-9]{1,}")(.*[^>]>)@xiu';

	// Replace mit callback Funktion
	return preg_replace_callback($pattern,'imgReplace_callback',$output);
}

?>