 <?
// При отправке почты, все не латинские символы в заголовках кодируется,
// например тема письма может выглядеть так =?windows-1251?B?7/Du4uXw6uA=?=
// вот такие тексты и будет преобразовывать эта функция
function decode_mime_string($subject) {
    $string = $subject;
    if(($pos = strpos($string,"=?")) === false) return $string;
   $prefix=substr($string,0,$pos);
    while(!($pos === false)) {
        $string = substr($string,$pos+2,strlen($string));
        $intpos = strpos($string,"?");
        $charset = substr($string,0,$intpos);
        $enctype = strtolower(substr($string,$intpos+1,1));
        $string = substr($string,$intpos+3,strlen($string));
        $endpos = strpos($string,"?=");
        $mystring = substr($string,0,$endpos);
        $string = substr($string,$endpos+2,strlen($string));
        if($enctype == "q") $mystring = quoted_printable_decode(ereg_replace("_"," ",$mystring));
        else if ($enctype == "b") $mystring = base64_decode($mystring);
        $newresult .= iconv($charset,"CP1251",$mystring);
        $pos = strpos($string,"=?");
    }
    $result = $prefix.$newresult.$string;
    return $result;
}


// перекодировщик тела письма.
// Само письмо может быть закодировано и данная функция приводит тело письма в нормальный вид.
// Так же и вложенные файлы будут перекодироваться этой функцией.
function compile_body($body,$enctype,$ctype) {
    $enctype = explode(" ",$enctype); 
	$enctype = $enctype[0];
    if(strtolower($enctype) == "base64")
    $body = base64_decode($body);
    elseif(strtolower($enctype) == "quoted-printable")
    $body = quoted_printable_decode($body);
    if(ereg("koi8", $ctype)) $body = convert_cyr_string($body, "k", "w");
    return $body;
}

// Функция для выдергивания метки boundary из заголовка Content-Type
// boundary это разделитель между разным содержимым в письме,
// например, чтобы отделить файл от текста письма
function get_boundary($ctype){
    if(preg_match('/boundary[ ]?=[ ]?(["]?.*)/i',$ctype,$regs)) {
        //$boundary = preg_replace('/^\"(.*)\"$/', "\1", $regs[1]);
        $boundary = $regs[1];
        $boundary=str_replace('"','',$boundary);
        $boundary=str_replace('\'','',$boundary);
        return rtrim("--$boundary");
    }
}

// если письмо будет состоять из нескольких частей (текст, файлы и т.д.)
// то эта функция разобьет такое письмо на части (в массив), согласно разделителю boundary
function split_parts($boundary,$body) {
    $startpos = strpos($body,$boundary)+strlen($boundary)+2;
    $lenbody = strpos($body,"$boundary--") - $startpos;
    $body = substr($body,$startpos,$lenbody);
    return explode($boundary,rtrim($body));
}

// Эта функция отделяет заголовки от тела.
// и возвращает массив с заголовками и телом
function fetch_structure($email) {
    $ARemail = Array();
    $separador = "\r\n\r\n";
    $header = trim(substr($email,0,strpos($email,$separador)));
    $bodypos = strlen($header)+strlen($separador);
    $body = substr($email,$bodypos,strlen($email)-$bodypos);
    $ARemail["header"] = $header;
    $ARemail["body"] = $body;
    return $ARemail;
}

// разбирает все заголовки и выводит массив, в котором каждый элемент является соответсвующим заголовком
function decode_header($header) {
    $headers = explode("\r\n",$header);
    $decodedheaders = Array();
    for($i=0;$i<count($headers);$i++) {
        $thisheader = trim($headers[$i]);
        if(!empty($thisheader))
        if(!ereg("^[A-Z0-9a-z_-]+:",$thisheader))
        $decodedheaders[$lasthead] .= " $thisheader";
        else {
            $dbpoint = strpos($thisheader,":");
            $headname = strtolower(substr($thisheader,0,$dbpoint));
            $headvalue = trim(substr($thisheader,$dbpoint+1));
            if($decodedheaders[$headname] != "") $decodedheaders[$headname] .= "; $headvalue";
            else $decodedheaders[$headname] = $headvalue;
            $lasthead = $headname;
        }
    }
    return $decodedheaders;
}

// эта функция нам уже знакома. она получает данные и реагирует на точку, которая ставится сервером в конце вывода.
function get_data($pop_conn)
{
    $data="";
    while (!feof($pop_conn)) {
        $buffer = chop(fgets($pop_conn,1024));
        $data .= "$buffer\r\n";
        if(trim($buffer) == ".") break;
    }
    return $data;
}
?> 