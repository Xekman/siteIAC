<?
	function CreateRandomNumeric($length)
	{
		randomize;
		$value = '';
		for ($i = 0; $i < $length; $i++)
		{
			$value = $value.chr(rand(48,57));
		}
		return $value;
	}

	function CreateRandomPassword($length)
	{
		randomize;
		$value = '';
		for ($i = 0; $i < $length; $i++)
		{
			$z = rand(0,2);
			if ($z == 0)
			{
				$value = $value.chr(rand(65,90));
			}
			if ($z == 1)
			{
				$value = $value.chr(rand(97,122));
			}
			if ($z == 2)
			{
				$value = $value.chr(rand(48,57));
			}
		}
		return $value;
	}

	function CreateRandomPasswordRequest($length)
	{
		randomize;
		$value = '';
		for ($i = 0; $i < $length; $i++)
		{
			$z = rand(0,1);
			if ($z == 0)
			{
				$value = $value.chr(rand(65,90));
			}
			if ($z == 1)
			{
				$value = $value.chr(rand(48,57));
			}
		}
		return $value;
	}
?>