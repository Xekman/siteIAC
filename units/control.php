<?
	class TPostObject
	{
		var $name;
		var $index2;
		var $value;
		var $check;
		var $min;
		var $max;

		function SetName($value)
		{
			$this->name = $value;
		}
		function GetName()
		{
			return $this->name;;
		}

		function SetValue($value)
		{
			$this->value = $value;
		}
		function GetValue()
		{
			return $this->value;
		}

		function SetCheck($value)
		{
			$this->check = $value;
		}
		function GetCheck()
		{
			return $this->check;
		}

		function SetMin($value)
		{
			$this->min = $value;
		}
		function GetMin()
		{
			return $this->min;
		}
		function SetMax($value)
		{
			$this->max = $value;
		}
		function GetMax()
		{
			return $this->max;
		}
		function SetMinMax($value_min, $value_max)
		{
			$this->min = $value_min;
			$this->max = $value_max;
		}
	}

	class THintObject
	{
		var $text;

		function SetText($value)
		{
			$this->text = $value;
		}
		function GetText()
		{
			return $this->text;;
		}
	}

	function AddTextHintObject(&$object, $text)
	{
		$object[] = New THintObject;
		$object[(sizeof($object) - 1)]->SetText($text);
	}

	function NewPostObject($count)
	{

		for ($i = 0; $i < $count; $i++)
		{
			$post_object[] =  New TPostObject;
			$post_object[$i]->SetCheck(true);
		}
		return $post_object;
	}

	function CheckStringLengthPostObject(&$object, $index)
	{
		return CheckStringLength($object[$index]->GetValue(), $object[$index]->GetMin(), $object[$index]->GetMax());
	}

	function CheckPostObject(&$object)
	{
		for ($i = 0; $i < sizeof($object); $i++)
		{
			if ($object[$i]->GetCheck() == false)
			{
				return false;
				break;
			}
		}
		return true;
	}

	function ForwardMappingPostObject(&$object)
	{
		for ($i = 0; $i < sizeof($object); $i++)
		{
			$object[$i]->SetValue('');
			$temp = $object[$i]->GetName();
			if (isset($_POST[$temp]))
			{
				$object[$i]->SetValue(mysql_escape_string(protect_syschar(trim($_POST[$temp]))));
			}
		}
	}

	function ForwardProtectPostObject(&$object)
	{
		for ($i = 0; $i < sizeof($object); $i++)
		{
			$object[$i]->SetValue(mysql_escape_string(protect_syschar(trim($object[$i]->GetValue()))));
		}
	}

	function BackMappingPostObject(&$object)
	{
		for ($i = 0; $i < sizeof($object); $i++)
		{
			$object[$i]->SetValue(stripslashes(protect_backslash($object[$i]->GetValue())));
		}
	}
?>