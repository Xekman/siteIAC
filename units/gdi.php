<?
	function RGBToHex($color, $prefix = false) 
	{
		if ($prefix)
		{
			$result = '#';
		}else{
			$result = '';
		}
		$result = $result.str_pad(dechex($color[0]), 2, '0', STR_PAD_LEFT);
		$result = $result.str_pad(dechex($color[1]), 2, '0', STR_PAD_LEFT);
		$result = $result.str_pad(dechex($color[2]), 2, '0', STR_PAD_LEFT);

		return $result;
	}

	function HexToRGB($hex) 
	{
		$result = array();

		$hex = str_replace('#', '', trim($hex));
	 
		if(strlen($hex) == 3) 
		{
			$result[0] = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
			$result[1] = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
			$result[2] = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
		}else{
			$result[0] = hexdec(substr($hex, 0, 2));
			$result[1] = hexdec(substr($hex, 2, 2));
			$result[2] = hexdec(substr($hex, 4, 2));
		}
		return $result;
	}

	function MinVal($a, $b)
	{
		if ($a > $b)
		{
			return $b;
		}else{
			return $a;
		}
	}

	function MaxVal($a, $b)
	{
		if ($a > $b)
		{
			return $a;
		}else{
			return $b;
		}
		return $result;
	}

	function GetLDChangeColor($hex, $lighten, $level)
	{
		$color = HexToRGB($hex);
		if ($lighten)
		{
			$color[0] = MinVal($color[0] + $level, 255);
			$color[1] = MinVal($color[1] + $level, 255);
			$color[2] = MinVal($color[2] + $level, 255);
		}else{
			$color[0] = MaxVal($color[0] - $level, 0);
			$color[1] = MaxVal($color[1] - $level, 0);
			$color[2] = MaxVal($color[2] - $level, 0);
		}
		return RGBToHex($color);
	}

	function GetSetupImageSize($image_width, $image_height, $body_width, $body_height)
	{
		$result = array();

		if ($image_width > $body_width)
		{
			$wp = ($body_width * 100) / $image_width;

			$new_h = ($image_height * $wp) / 100;
			$new_w = $body_width;
		}else{
			$new_h = $image_height;
			$new_w = $image_width;
		}

		if ($new_h > $body_height)
		{
			$hp = ($body_height * 100) / $new_h;

			$new_w = ($new_w * $hp) / 100;
			$new_h = $body_height;
		}

		$result[0] = round($new_w);
		$result[1] = round($new_h);

		return $result;
	}
	
	function CreateSetupJPEGImage($input, $output, $width, $height, $quality = 100)
	{
		$result = false;
		if (!file_exists($input))
		{
			return false;
		}

		$size = getimagesize($input);
		if(!$size)
		{
			return false;
		}
		
		$func = 'imagecreatefrom'.strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
		if(!function_exists($func))
		{
			return false;
		}
		
		$im = $func($input);
		
		if (!$im)
		{
			return false;
		}

		$size2 = GetSetupImageSize($size[0], $size[1], $width, $height);

		$im2 = imagecreatetruecolor($size2[0], $size2[1]);

		if (!imagecopyresampled($im2, $im, 0, 0, 0, 0, $size2[0], $size2[1], $size[0], $size[1]))
		{
			return false;
		}

		imagedestroy($im);

		if (file_exists($output))
		{
			unlink($output);
		}
		if (!imagejpeg($im2, $output, $quality))
		{
			return false;
		}

		imagedestroy($im2);
		
		return true;
	}
?>