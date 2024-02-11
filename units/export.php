<?
	function ReplaceExportMark($str, $find, $first = true)
	{
		$temp_data_index = strpos($str, $find);
		if ($temp_data_index != '')
		{
			if ($first)
			{
				return substr($str, 0, $temp_data_index);
			}else{
				return substr($str, $temp_data_index + 1, strlen($str) - 1);
			}
		}else{
			return $str;
		}
	}

	class clsExportWord
	{
		var $handle;

		function clsExportWord($terminatetimeout = 0)
		{
			try
			{
				if ($terminatetimeout > 0)
				{
					terminateforgotlastofficeapplication('winword.exe', $terminatetimeout);
				}					
				$this->handle = new COM("word.application");				

				return true;
			}catch(Exception $e){
				return false;
			}			   
		}
		function DisplayAlerts($enabled = true)
		{
			try
			{
				$this->handle->DisplayAlerts = $enabled;
				
				return true;
			}catch(Exception $e){
				return false;
			}			   
		}
		function ScreenUpdating($enabled = true)
		{
			try
			{
				$this->handle->ScreenUpdating = $enabled;
				
				return true;
			}catch(Exception $e){
				return false;
			}			   
		}		
		function EnableEvents($enabled = true)
		{
			try
			{
				$this->handle->EnableEvents = $enabled;
				
				return true;
			}catch(Exception $e){
				return false;
			}			   
		}			
		function Open($file, $readonly = true)
		{
			try
			{
				$this->handle->Documents->Open($file, null, $readonly);
				
				return true;
			}catch(Exception $e){
				return false;
			}
		}
		function Show()
		{
			try
			{
				$this->handle->Visible = true;

				return true;
			}catch(Exception $e){
				return false;
			}
		   
		}
		function GetBookmarkCount()
		{
			try
			{
				return $this->handle->ActiveDocument->Bookmarks->Count;
			}catch(Exception $e){
				return 0;
			}
		}
		function GetBookmarkName($index)
		{
			try
			{
				return $this->handle->ActiveDocument->Bookmarks[$index]->Name;
			}catch(Exception $e){
				return '';
			}
		}
		function SetBookmarkText($bookmark ,$text)
		{
			try
			{
				$this->handle->ActiveDocument->Bookmarks($bookmark)->Range->Text = $text;
				return true;
			}catch(Exception $e){
				return false;
			}
		}
		function SetBookmarkTextToIndex($index, $text)
		{
			try
			{
				$this->handle->ActiveDocument->Bookmarks[$index]->Range->Text = $text;
				return true;
			}catch(Exception $e){
				return false;
			}
		}
		function SetBookmarkPictureToIndex($index, $path)
		{
			try
			{
				if (file_exists($path))
				{
					$this->handle->ActiveDocument->Bookmarks[$index]->Range->InlineShapes->AddPicture($path);
				}
				return true;
			}catch(Exception $e){
				return false;
			}
		}
		function SaveAs($file)
		{
			try
			{
				$this->handle->ActiveDocument->SaveAs($file);
				return true;
			}catch(Exception $e){
				return false;
			}	   
		}
		function Save()
		{
			try
			{
				$this->handle->ActiveDocument->Save();
				return true;
			}catch(Exception $e){
				return false;
			}
		}     
		function Quit($release = false)
		{
			if( $this->handle)
			{
				try 
				{
					$this->handle->Quit();
					if ($release)
					{
						$this->handle->Release();
					}
					$this->handle = null;

					return true;
				}catch(Exception $e){
					return false;
				}
			}
		}
		function NewDocument()
		{
		   $this->handle->Documents->Add();
		}
		function WriteText( $Text )
		{
		   $this->handle->Selection->Typetext( $Text );
		}   
		function Close()
		{
		   $this->handle->ActiveDocument->Close();
		}
		function DocumentCount()
		{
		   return $this->handle->Documents->Count;
		}
		function GetVersion()
		{
		   return $this->handle->Version;
		}
		function GetHandle()
		{
		   return $this->handle;
		}
		function LockDoc($password, $type = 3)
		{
			try
			{
				$this->handle->ActiveDocument->Protect($type, false, $password, false, false);
				return true;
			}catch(Exception $e){
				return false;
			}
		}		
	}

	class clsExportExcel
	{
		var $handle;

		function clsExportExcel($terminatetimeout = 0)
		{
			try
			{
				if ($terminatetimeout > 0)
				{
					terminateforgotlastofficeapplication('excel.exe', $terminatetimeout);
				}
				$this->handle = new COM("excel.application");
				
				return true;
			}catch(Exception $e){
				return false;
			}			   
		}
		function DisplayAlerts($enabled = true)
		{
			try
			{
				$this->handle->DisplayAlerts = $enabled;

				return true;
			}catch(Exception $e){
				return false;
			}			   
		}
		function ScreenUpdating($enabled = true)
		{
			try
			{
				$this->handle->ScreenUpdating = $enabled;

				return true;
			}catch(Exception $e){
				return false;
			}			   
		}		
		function EnableEvents($enabled = true)
		{
			try
			{
				$this->handle->EnableEvents = $enabled;

				return true;
			}catch(Exception $e){
				return false;
			}			   
		}	
		function CutCopyMode($enabled = true)
		{
			try
			{
				$this->handle->CutCopyMode = $enabled;

				return true;
			}catch(Exception $e){
				return false;
			}			   
		}			
		function ClipboardFormats($index = 1)
		{
			try
			{
				return $this->handle->ClipboardFormats($index);
			}catch(Exception $e){
				return false;
			}			   
		}					
		function Open($file, $readonly = false)
		{
			try
			{
				$this->handle->Workbooks->Open($file, null, $readonly);

				return true;
			}catch(Exception $e){
				return false;
			}
		}
		function Show()
		{
			try
			{
				$this->handle->Visible = true;

				return true;
			}catch(Exception $e){
				return false;
			}
		   
		}
		function Quit($release = false)
		{
			if( $this->handle)
			{
				try 
				{
					$this->handle->Quit();
					if ($release)
					{
						$this->handle->Release();
					}
					$this->handle = null;
					return true;
				}catch(Exception $e){
					return false;
				}
			}
		}	  
		function GetWorksheetCount()
		{
			try
			{
				return $this->handle->ActiveWorkBook->Worksheets->Count;
			}catch(Exception $e){
				return 0;
			}
		}		  
		function GetWorksheetName($index)
		{
			try
			{
				return $this->handle->ActiveWorkBook->Worksheets[$index]->Name;
			}catch(Exception $e){
				return '';
			}
		}	  
		function SetWorksheetName($index, $name)
		{
			try
			{
				return $this->handle->ActiveWorkBook->Worksheets[$index]->Name = $name;
				return true;
			}catch(Exception $e){
				return false;
			}
		}
		function SaveAs($file)
		{
			try
			{
				$this->handle->ActiveWorkBook->SaveAs($file);
				return true;
			}catch(Exception $e){
				return false;
			}	   
		}
		function Save()
		{
			try
			{
				$this->handle->ActiveWorkBook->Save();
				return true;
			}catch(Exception $e){
				return false;
			}
		}  

		function ExistsName($index, $name)
		{
			try
			{
				$this->handle->ActiveWorkBook->Worksheets[$index]->Range($name);
				return true;
			}catch(Exception $e){
				return false;
			}
		}     
		function GetNameRow($index, $name)
		{
			try
			{
				return $this->handle->ActiveWorkBook->Worksheets[$index]->Range($name)->Row;
				//return true;
			}catch(Exception $e){
				return 0;
			}
		}  

		function GetNameRowEx($formatworksheet, $index, $name)
		{
			try
			{
				$k = -1;
				if ($k == -1)
				{
					$temp_name = $name;
					if ($this->ExistsName($index, $temp_name)){$k = $this->GetNameRow($index, $temp_name);}
				}
				if ($k == -1) 
				{
					$temp_name = $formatworksheet.$name;
					if ($this->ExistsName($index, $temp_name)){$k = $this->GetNameRow($index, $temp_name);}
				}
				return $k;
			}catch(Exception $e){
				return -1;
			}
		} 

		function GetNames($index)
		{
			$row = array();
			try
			{
				for ($i = 1; $i <= $this->handle->ActiveWorkBook->Names->Count; $i++)
				{
					if ($this->handle->ActiveWorkBook->Names[$i]->RefersToRange->Worksheet->Name == $this->handle->ActiveWorkBook->Worksheets[$index]->Name)
					{
						$row[] = $this->handle->ActiveWorkBook->Names[$i]->Name;
					}
				}
				return $row;
			}catch(Exception $e){
				return $row;
			}
		}
		function GetNamesEx($index, $ascopynames, $copyrow = -1)
		{
			$row = array();
			try
			{
				for ($i = 1; $i <= $this->handle->ActiveWorkBook->Names->Count; $i++)
				{
					if ($this->handle->ActiveWorkBook->Names[$i]->RefersToRange->Worksheet->Name == $this->handle->ActiveWorkBook->Worksheets[$index]->Name)
					{

						$cell = $this->handle->ActiveWorkBook->Names[$i]->Name;

						$b = true;
						if ($copyrow != -1)
						{

							if ($this->handle->ActiveWorkBook->Worksheets[$index]->Range($cell)->Row == $copyrow)
							{
								$b = false;
								if ($ascopynames)
								{
									$row[] = $cell;
								}
							}
						}
						if ($b)
						{
							if (!$ascopynames)
							{
								$row[] = $cell;
							}
						}
					}
				}
				return $row;
			}catch(Exception $e){
				return $row;
			}
		}
		function SetName($index, $name, $text)
		{
			try
			{
				$this->handle->ActiveWorkBook->Worksheets[$index]->Range($name)->Value = $text;
				return true;
			}catch(Exception $e){
				return false;
			}
		}
		function CopyRow($index, $copy, $new)
		{
			try
			{
				$this->handle->ActiveWorkBook->Worksheets[$index]->Rows[$copy]->Copy;
				$this->handle->ActiveWorkBook->Worksheets[$index]->Rows[$new]->Insert;
				return true;
			}catch(Exception $e){
				return false;
			}
		}
		function DeleteRow($index, $delete)
		{
			try
			{
				$this->handle->ActiveWorkBook->Worksheets[$index]->Rows[$delete]->Delete;
				return true;
			}catch(Exception $e){
				return false;
			}
		}
		function CopyPage($index, $copya, $copyb, $count)
		{
			try
			{
				for ($i = 0; $i < $count; $i++)
				{
					$this->handle->ActiveWorkBook->Worksheets[$index]->Rows[$copya + $i]->Copy;
					$this->handle->ActiveWorkBook->Worksheets[$index]->Rows[$copyb + $i]->Insert;
				}
				return true;
			}catch(Exception $e){
				return false;
			}
		}
		function GetNamesCount()
		{
			try
			{
				return $this->handle->ActiveWorkBook->Names->Count;
			}catch(Exception $e){
				return 0;
			}

		}
		function GetNamesName($index)
		{
			try
			{
				return $this->handle->ActiveWorkBook->Names[$index]->Name;
			}catch(Exception $e){
				return '';
			}
		}
		function SetCellText($index, $x, $y, $text)
		{
			try
			{
				$this->handle->Worksheets[$index]->Cells($y, $x)->Value = $text;  
				return true;
			}catch(Exception $e){
				return false;
			}
		}		
		function LockDoc($worksheet, $password)
		{
			try
			{
				$worksheet->Protect($password);
				return true;
			}catch(Exception $e){
				return false;
			}
		}		
		function SetCellPicture($range, $path)
		{
			try
			{
				if (file_exists($path))
				{
					//$image = $range->Parent->Pictures->Insert($path);
					//$image->Copy();
					//$image = $range->Parent->Pictures->Paste();
					//$image->Left = $range->Left;
					//$image->Top = $range->Top;

					list($width, $height, $type, $attr) = getimagesize($path);
					$range->Parent->Shapes->AddPicture($path, false, true, $range->Left, $range->Top, (($width * 72) / 96), (($height * 72) / 96));
					//$range->Parent->Pictures->Reset();
				}
				return true;
			}catch(Exception $e){
				return false;
			}
		}
		function GetVersion()
		{
		   return $this->handle->Version;
		}
		function GetHandle()
		{
		   return $this->handle;
		}
		function GetNamesSpace($index)
		{
			$row = array();
			try
			{
				$book_index = $this->handle->ActiveWorkBook->Worksheets[$index]->Index;
				for ($i = 1; $i <= $this->handle->ActiveWorkBook->Names->Count; $i++)
				{
					$call_index = $this->handle->ActiveWorkBook->Names[$i]->RefersToRange->Worksheet->Index;
					if ($call_index = $book_index)
					{
						$row[] = $call_index;
					}

					if ($this->handle->ActiveWorkBook->Names[$i]->RefersToRange->Worksheet->Name == $this->handle->ActiveWorkBook->Worksheets[$index]->Name)
					{

						$cell = $this->handle->ActiveWorkBook->Names[$i]->Name;

						$b = true;
						if ($copyrow != -1)
						{

							if ($this->handle->ActiveWorkBook->Worksheets[$index]->Range($cell)->Row == $copyrow)
							{
								$b = false;
								if ($ascopynames)
								{
									$row[] = $cell;
								}
							}
						}
						if ($b)
						{
							if (!$ascopynames)
							{
								$row[] = $cell;
							}
						}
					}

				}
				return $row;
			}catch(Exception $e){
				return $row;
			}
		}  
	}

	function NamesToMarks($names)
	{
		$row = array();
		for ($i = 0; $i < sizeof($names); $i++)
		{
			$row[] = ReplaceExportMark(ReplaceExportMark($names[$i], '!', false), '_');
		}
		return $row;
	}
	function MarksToFlags($marks, $reservname)
	{
		$row = array();
		for ($i = 0; $i < sizeof($marks); $i++)
		{
			$flag = false;
			for ($j = 0; $j < sizeof($reservname); $j++)
			{
				if ($marks[$i] == $reservname[$j])
				{
					$flag = true;
					break;
				}
			}
			$row[] = $flag;
		}
		return $row;
	}

	function FormatWorksheetName($worksheet)
	{
		$k = 0;
		if ($k == 0) {$k = strpos($worksheet, ' ');}
		if ($k == 0) {$k = strpos($worksheet, '-');}
		if ($k != 0) {
			return chr(39).$worksheet.chr(39).'!';
		}else{
			return $worksheet.'!';
		}
	}

	function ExportToWord($input, $output, &$data, $lock, $terminatetimeout = 0)
	{
		try 
		{
			$RESERVNAME_IMAGE = 'i';

			$word = new clsExportWord($terminatetimeout);
			$word->DisplayAlerts(false);
			$word->ScreenUpdating(false);
			$word->EnableEvents(false);
			$word->Open($input);
			$c = $word->GetBookmarkCount();
			if ($c > 0)
			{
				for ($i = $c; $i >= 1; $i--)
				{
					$bookmark = $word->GetBookmarkName($i);
					if ($bookmark[0] == $RESERVNAME_IMAGE)
					{
						$word->SetBookmarkPictureToIndex($i, $data[ReplaceExportMark($bookmark, '_')]);
					}else{
						$word->SetBookmarkTextToIndex($i, $data[ReplaceExportMark($bookmark, '_')]);
					}
				}   
			}
			if ($lock)
			{
				$word->LockDoc(CreateRandomPassword(128));
			}			
			//$word->DisplayAlerts(true);
			//$word->Show();
			if (file_exists($output))
			{
				unlink($output);
			}
			$word->SaveAs($output);
			$word->Quit();
			unset($word);
			return true;
		}catch(Exception $e){
			$word->Quit();
			return false;
		}
	}	


	function ExportToExcel($input, $output, &$data, &$lists, $lock, $terminatetimeout = 0)
	{
		try 
		{
			$excel = new clsExportExcel($terminatetimeout);
			$excel->DisplayAlerts(false);
			$excel->ScreenUpdating(false);
			$excel->EnableEvents(false);
			$excel->Open($input);

			$RESERVNAME_COPY = 'copy';
			$RESERVNAME_COPYNUM = 'copynum';
			$RESERVNAME_COPYINDEX = 'copyindex';
			$RESERVNAME_COPYCOUNT = 'copycount';
			$RESERVNAME_PAGENUM = 'pagenum';
			$RESERVNAME_PAGECOUNT = 'pagecount';
			$RESERVNAME_LIST = 'list';
			$RESERVNAME_PAGE = 'page';
			$RESERVNAME_IMAGE = 'i';

			$xlLastCell = 11;

			$reservname_data = array();
			$reservname_data[] = $RESERVNAME_COPY;
			$reservname_data[] = $RESERVNAME_COPYNUM;
			$reservname_data[] = $RESERVNAME_COPYCOUNT;
			$reservname_data[] = $RESERVNAME_LIST;
			$reservname_data[] = $RESERVNAME_PAGE;
			$reservname_data[] = $RESERVNAME_PAGENUM;
			$reservname_data[] = $RESERVNAME_PAGECOUNT;

			for ($i = 1; $i <= $excel->GetWorksheetCount(); $i++)
			{
				$worksheet = $excel->handle->ActiveWorkBook->Worksheets[$i];
				$worksheet_name = FormatWorksheetName($worksheet->Name);
				$worksheet_index = $worksheet->Index;

				$copy_mode = false;

				$list_count = 0;
				$lists_index = 0;
				if (is_array($lists))
				{
					if ($excel->ExistsName($i, $worksheet_name.$RESERVNAME_COPYINDEX))
					{				
						$temp = $worksheet->Range($worksheet_name.$RESERVNAME_COPYINDEX)->Value;
						if (is_numeric($temp) and $temp >= 1 and $temp <= sizeof($lists))
						{
							$lists_index = $temp - 1;
						}
					}
					
					$list = $lists[$lists_index];
				}
				
				if (is_array($list))
				{
					$list_count = sizeof($list);
					if ($excel->ExistsName($i, $worksheet_name.$RESERVNAME_COPY))
					{
						$copy_range = $worksheet->Range($worksheet_name.$RESERVNAME_COPY);
						$copy_x1 = $copy_range->Column;
						$copy_y1 = $copy_range->Row;
						$copy_w = $copy_range->Columns->Count;
						$copy_h = $copy_range->Rows->Count;
						$copy_x2 = $copy_x1 + $copy_w - 1;
						$copy_y2 = $copy_y1 + $copy_h - 1;

						$copy_mode = true;

						$copy_range_names = array();
						$copy_range_index = 0;
					}
				}
				$page_range_names = array();
				$page_range_index = 0;
				for ($j = 1; $j <= $excel->handle->ActiveWorkBook->Names->Count; $j++)
				{
					$names_worksheet_index = $excel->handle->ActiveWorkBook->Names[$j]->RefersToRange->Worksheet->Index;
					if ($worksheet_index == $names_worksheet_index)
					{
						$names_name = $excel->handle->ActiveWorkBook->Names[$j]->Name;
						$b = false;
						if ($copy_mode)
						{
							$names_x = $worksheet->Range($names_name)->Column;
							$names_y = $worksheet->Range($names_name)->Row;
							if ($names_x >= $copy_x1 and $names_x <= $copy_x2 and $names_y >= $copy_y1 and $names_y <= $copy_y2)
							{
								$b = true;
							}
						}
						if ($b)
						{
							$copy_range_names[$copy_range_index] = $names_name;
							$copy_range_index = $copy_range_index + 1;

							$copy_range_marks = NamesToMarks($copy_range_names);
							$copy_range_flags = MarksToFlags($copy_range_marks, $reservname_data);
						}else{
							$page_range_names[$page_range_index] = $names_name;
							$page_range_index = $page_range_index + 1;

							$page_range_marks = NamesToMarks($page_range_names);
							$page_range_flags = MarksToFlags($page_range_marks, $reservname_data);
						}
					}
				}

				$page_count = 1;
				if ($copy_mode)
				{
					$list_mode = false;
					if ($excel->ExistsName($i, $worksheet_name.$RESERVNAME_LIST))
					{
						$list_h = $worksheet->Range($worksheet_name.$RESERVNAME_LIST)->Rows->Count;
						$copy_list_count = Ceil($list_h / $copy_h);
						$page_count = Ceil($list_count / $copy_list_count);
						$list_mode = true;
					}
				}
				if ($list_mode)
				{
					if ($excel->ExistsName($i, $worksheet_name.$RESERVNAME_PAGE))
					{
						$page_range = $worksheet->Range($worksheet_name.$RESERVNAME_PAGE);
						$page_x1 = $page_range->Column;
						$page_y1 = $page_range->Row;
						$page_w = $page_range->Columns->Count;
						$page_h = $page_range->Rows->Count;
						$page_x2 = $page_x1 + $page_w - 1;
						$page_y2 = $page_y1 + $page_h - 1;
					}else{
						$page_x1 = 1;
						$page_y1 = 1;
						$page_x2 = $worksheet->UsedRange->SpecialCells($xlLastCell)->Column;
						$page_y2 = $worksheet->UsedRange->SpecialCells($xlLastCell)->Row;
						$page_w = $page_x2 - $page_x1 + 1;
						$page_h = $page_y2 - $page_y1 + 1;
						$page_range = $worksheet->Range($worksheet->Cells($page_y1, $page_x1), $worksheet->Cells($page_y2, $page_x2));
					}
				}

				for ($j = 0; $j < sizeof($page_range_names); $j++)
				{
					if ($page_range_flags[$j])
					{
						$b = false;
						if (!$b and $page_range_marks[$j] == $RESERVNAME_PAGECOUNT)
						{
							$text =  $page_count;
							$b = true;
						}
						if (!$b and $page_range_marks[$j] == $RESERVNAME_COPYCOUNT)
						{
							$text =  $list_count;
							$b = true;
						}
						if ($b)
						{
							$worksheet->Range($page_range_names[$j])->Value = $text;
						}
					}else{
						if ($page_range_marks[$j][0] == $RESERVNAME_IMAGE)
						{
							$excel->SetCellPicture($worksheet->Range($page_range_names[$j]), $data[$page_range_marks[$j]]);
						}else{
							$worksheet->Range($page_range_names[$j])->Value = $data[$page_range_marks[$j]];
						}
					}
				}
				if ($copy_mode)
				{
					$copy_row = $list_count;
					if ($list_mode)
					{
						$max_list_count = $copy_list_count - (($page_count * $copy_list_count) - $list_count);
						for ($p = $page_count - 1; $p >= 0; $p--)
						{
							for ($j = 0; $j < sizeof($page_range_names); $j++)
							{
								if ($page_range_flags[$j])
								{
									if ($page_range_marks[$j] == $RESERVNAME_PAGENUM) {$worksheet->Range($page_range_names[$j])->Value = ($p + 1);}
								}
							}
							for ($z = $max_list_count - 1; $z >= 0; $z--)
							{
								for ($j = 0; $j < sizeof($copy_range_names); $j++)
								{
									if ($copy_range_flags[$j])
									{
										if ($copy_range_marks[$j] == $RESERVNAME_COPYNUM) {$worksheet->Range($copy_range_names[$j])->Value = $copy_row;}
									}else{
										$worksheet->Range($copy_range_names[$j])->Value = $list[$copy_row - 1][$copy_range_marks[$j]];
									}
								}
								if ($z != 0)
								{
									/*
									$copy_range->Copy;
									$copy_yx1 = $worksheet->Cells($copy_y1 + ($copy_h * $z), $copy_x1);
									$copy_yx2 = $worksheet->Cells($copy_y2 + ($copy_h * $z), $copy_x2);
									$worksheet->Range($copy_yx1, $copy_yx2)->PasteSpecial();
									*/		
									$worksheet->Range($worksheet->Cells($copy_y1 + ($copy_h * $z), $copy_x1), $worksheet->Cells($copy_y2 + ($copy_h * $z), $copy_x2))->Value = $copy_range->Value;
								}
								$copy_row = $copy_row - 1;
							}
							if ($p != 0)
							{
								/*
								$page_range->EntireRow->Copy;
								$page_yx1 = $worksheet->Cells($page_y1 + $page_h, $page_x1);
								$page_yx2 = $worksheet->Cells($page_y2 + $page_h, $page_x2);
								$worksheet->Range($page_yx1, $page_yx2)->Insert;
								*/							
								$worksheet->Range($worksheet->Cells($page_y1 + $page_h, $page_x1), $worksheet->Cells($page_y2 + $page_h, $page_x2))->EntireRow->Insert;							
								$page_range->EntireRow->Copy($worksheet->Range($worksheet->Cells($page_y1 + $page_h, $page_x1), $worksheet->Cells($page_y2 + $page_h, $page_x2)));								
							}
							$max_list_count = $copy_list_count;
						}
					}else{
						for ($z = $copy_row - 1; $z >= 0; $z--)
						{
							for ($j = 0; $j < sizeof($copy_range_names); $j++)
							{
								if ($copy_range_flags[$j])
								{
									if ($copy_range_marks[$j] == $RESERVNAME_COPYNUM) {$worksheet->Range($copy_range_names[$j])->Value = $copy_row;}
								}else{
									$worksheet->Range($copy_range_names[$j])->Value = $list[$z][$copy_range_marks[$j]];
								}
							}
							if ($z != 0)
							{
								/*	
								$copy_range->EntireRow->Copy;
								$copy_yx1 = $worksheet->Cells($copy_y1 + $copy_h, $copy_x1);
								$copy_yx2 = $worksheet->Cells($copy_y2 + $copy_h, $copy_x2);
								$worksheet->Range($copy_yx1, $copy_yx2)->Insert;
								*/									
								$worksheet->Range($worksheet->Cells($copy_y1 + $copy_h, $copy_x1), $worksheet->Cells($copy_y2 + $copy_h, $copy_x2))->EntireRow->Insert;													
								$copy_range->EntireRow->Copy($worksheet->Range($worksheet->Cells($copy_y1 + $copy_h, $copy_x1), $worksheet->Cells($copy_y2 + $copy_h, $copy_x2)));
							}
							$copy_row = $copy_row - 1;
						}
					}
					$copy_range->ClearOutLine;
				}
				if ($lock)
				{
					$excel->LockDoc($worksheet, CreateRandomPassword(128));
				}
			}

			//$excel->DisplayAlerts(true);
			//$excel->Show();
			if (file_exists($output))
			{
				unlink($output);
			}
			$excel->SaveAs($output);
			$excel->Quit();
			unset($excel);
			return true;
		}catch(Exception $e){
			$excel->Quit();
			return false;
		}
	}

	function ExtractTextmark($text)
	{
		$result = array();
		$b = false;
		$mark = '%';
		$c = -1;
		for ($i = 0; $i < strlen($text); $i++)
		{
			if ($b and $text[$i] != $mark)
			{
				$result[$c] = $result[$c].$text[$i];
			}
			if ($text[$i] == $mark)
			{
				if ($b)
				{
					$b = false;
				}else{
					$b = true;
					$c = $c + 1;
				}
			}
		}
	
		return $result;		
	}	
	
	function ExportToTextData($input, &$data, $flinesize = 16384)
	{
		$fhwnd = fopen($input, "r");
		if ($fhwnd == false)
		{
			return false;
		}
		$result = '';
		while (!feof($fhwnd))
		{
			$result = $result.fgets($fhwnd, $flinesize);
		}
		fclose($fhwnd);
		$textmakr = ExtractTextmark($result);
		for ($i = 0; $i < sizeof($textmakr); $i++)
		{
			$result = str_replace('%'.$textmakr[$i].'%', $data[ReplaceExportMark($textmakr[$i], '_')], $result);
		}	
		return $result;
	}	
	
	function ExportToText($input, $output, &$data, $flinesize = 16384)
	{
		$text = ExportToTextData($input, $data, $flinesize);
		if ($text == false)
		{
			return false;
		}
		$fhwnd = fopen($output, "w");
		if ($fhwnd == false)
		{
			return false;
		}		
		if (fwrite($fhwnd, $text) == false)
		{
			return false;
		}
		fclose($fhwnd);
		return true;
	}
		function ExportToSQL($input, $output, &$data, $flinesize = 16384)
	{
		$text = ExportToTextData($input, $data, $flinesize);
		if ($text == false)
		{
			return false;
		}
		$fhwnd = fopen($output, "w");
		if ($fhwnd == false)
		{
			return false;
		}		
		if (fwrite($fhwnd, $text) == false)
		{
			return false;
		}
		fclose($fhwnd);
		return true;
	}

	
	function IsWordExpansion($expansion)
	{
		$expansion = strtolower($expansion);
		if ($expansion == '.doc')
		{
			return true;
		}else{
			return false;
		}
	}
	function IsNewWordExpansion($expansion)
	{
		$expansion = strtolower($expansion);
		if ($expansion == '.docx')
		{
			return true;
		}else{
			return false;
		}
	}
	function IsExcelExpansion($expansion)
	{
		$expansion = strtolower($expansion);
		if ($expansion == '.xls' or 
			$expansion == '.xlsx')
		{
			return true;
		}else{
			return false;
		}
	}
	function IsTextExpansion($expansion)
	{
		$expansion = strtolower($expansion);
		if ($expansion == '.txt')
		{
			return true;
		}else{
			return false;
		}
	}
	function IsSQLExpansion($expansion)
	{
		$expansion = strtolower($expansion);
		if ($expansion == '.sql')
		{
			return true;
		}else{
			return false;
		}
	}	
?>