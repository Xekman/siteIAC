<?
function IsYandexLocation($text)
	{
    $coordinate = explode(",", $text);
    if (count($coordinate)!=2)
    {
      return false;
    }
    for ($i = 1; $i<=2;$i++)
    {
         if (!is_numeric($coordinate[$i-1]))
		{
			return false;
		}
        $coordinate_x = explode(".", $coordinate[$i-1]);
        if (count($coordinate_x)!=2)
        {
            return false;
        }
    }
		return true;
	}
	?>