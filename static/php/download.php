<?
if( isset($_POST) && !empty($_POST) && isset($_POST['source_name']) && !empty($_POST['source_name']) && isset($_POST['display_name']) && !empty($_POST['display_name']) && isset($_POST['author']) && !empty($_POST['author']) && isset($_POST['issue']) && !empty($_POST['issue']))
{
// path to config file
$config = $_SERVER["DOCUMENT_ROOT"];
$config = $config."/open-records-generator/config/config.php";
require_once($config);

$config = $_SERVER["DOCUMENT_ROOT"];
$config = $config."/static/php/config_download.php";
require_once($config);

require_once("Zend/Pdf.php");
require_once("downloadPDF.php");

// debug
// ini_set('display_errors', 1);
// var_dump($_POST['source_name']);

$source_name = $_POST['source_name'];
$display_name = $_POST['display_name'];
$author = $_POST['author'];
$issue = $_POST['issue'];

// user must have write access to database
$db_download = db_connect_download('main');

// check format
$A4_issues = array(13, 14, 15, 16, 17);
$format = 'default';
if(in_array($issue, $A4_issues))
	$format = 'A4';
else if ($issue > 10)
	$format = 'small';
// $smallformat = ($issue > 10) ? true : false; 


// set font
$font_path = $root.'static/fonts/mtdbt2f-gg.ttf';
$font_size = $format == 'small' ? 5 : 7; 

$note_font_path = $root.'static/fonts/mtdbt2f-f.ttf';
$note_font_size = 13;




// 1. Init
$now = time();
$timestamp = strtoupper(date('Y M d g:i A', $now));
$name1 = trim($display_name);
$deck = trim($author);
$body = $_SERVER['REMOTE_ADDR'];
$notes = trim($source_name);
$created = $modified = date('Y-m-d H:i:s', $now);

// 2. Write database record
$allowed_repeat = 2;
$isAllowed = false;
$sql = "SELECT name1, body FROM downloads ORDER BY created DESC LIMIT " . $allowed_repeat;
// $previous_ips = array();
$res = $db_download->query($sql);

while($object = $res->fetch_assoc())
{
	if( $body != $object['body'] || $name1 != $object['name1']){
		$isAllowed = true;
	}
}

if($isAllowed)
{
	$keys = array('`name1`','`deck`','`body`','`created`','`modified`','`notes`');
	$values = array($name1, $deck, $body, $created, $created, $notes);
	$question_marks = [];
	$data_types = [];
	foreach($keys as $k){
	    $question_marks[] = '?';
	    $data_types[] = 's';
	}
	$keys = implode(", ", $keys);
	$question_marks = implode(", ", $question_marks);
	$data_types = implode("", $data_types);

	$sql = "INSERT INTO downloads (" . $keys . ") VALUES(" . $question_marks . ")";
	$stmt = $db_download->prepare($sql);
	$stmt->bind_param($data_types, ...$values);
	$res = $stmt->execute();
}

// 3. Load PDF (Zend_PDF)
$source_filename = $media_root.$source_name.".pdf";
$pdf = Zend_Pdf::load($source_filename);

// 4. Set metadata (Zend_PDF)
$pdf->properties['Title'] = $display_name;
$pdf->properties['Author'] = $author;
$gmt_offset = explode(":", date("P", $now));
$pdf->properties["ModDate"] = "D:".date("YmdHis", $now).$gmt_offset[0]."'".$gmt_offset[1]."'";

// 5. Time stamp pages (Zend_PDF)
$black = new Zend_Pdf_Color_Html('#000000');
$blue = new Zend_Pdf_Color_Html('#0000FF');
$white = new Zend_Pdf_Color_Html('#FFFFFF');
$stamp = "BoTSL#".$issue." ".$timestamp;

$bulletin_style = new Zend_Pdf_Style(); 
$bulletin_style->setFillColor($white); 
$bulletin_style->setLineColor($white); 
$bulletin_style->setFont(Zend_Pdf_Font::fontWithPath($font_path), $font_size);

if ($format == 'small') {
    foreach ($pdf->pages as $num => $obj) {
        $obj->setStyle($bulletin_style); 
        $obj->drawRectangle(22, 24, 120, 13);
        $obj->setFillColor($black); 
        $obj->drawText($stamp, 25, 17); // stamp
        $pdf->pages[$num] = $obj; // save
    }
} 
else if ($format == 'A4'){
	foreach ($pdf->pages as $num => $obj) {
        $obj->setStyle($bulletin_style); 
        $obj->drawRectangle(48, 46, 154, 40);
        $obj->setFillColor($black); 
        $obj->drawText($stamp, 49, 41); // stamp
        $pdf->pages[$num] = $obj; // save
    }
}else { 
    foreach ($pdf->pages as $num => $obj) {
        $obj->setStyle($bulletin_style); 
        $obj->drawRectangle(30, 32, 150, 20);
        $obj->setFillColor($black); 
        $obj->drawText($stamp, 32, 24); // stamp
        $pdf->pages[$num] = $obj; // save
    }
}

// 5a. note on the time exception 
if($display_name == "A-Note-on-the-Time") 
{
	$style = new Zend_Pdf_Style(); 
	$style->setFont(Zend_Pdf_Font::fontWithPath($note_font_path), $note_font_size);
	$style->setFillColor($white);
	
	$boxes = array();
	$stampText = date('Y M d g:i A', $now);
	
	$date1 = "2011-02-18 3:34"; // the time of writing
	$date2 = date('Y-M-d g:i', $now);
	$daylightSaving = date('I');
	$diff = abs(strtotime($date2) - strtotime($date1));
	$days = floor($diff / (60*60*24));
	$hours = floor((($diff - $days*60*60*24) / (60*60)));
	$minutes = floor(($diff - $days*60*60*24 - $hours*60*60)/ (60));
	$hours = $hours + $daylightSaving;
	$stampTextD = $days." days, ".$hours." hours, ".$minutes." minutes.";
	
	
	// 2d array -- page, x, y, w, h, text
	
	$boxes = array(	array( p=>2, x=>89, y=>602, w=>114, h=>15, t=>$stampText),
					array( p=>4, x=>174, y=>482, w=>114, h=>15, t=>$stampText),
					array( p=>5, x=>169, y=>617, w=>114, h=>15, t=>$stampText),
					array( p=>6, x=>51, y=>362, w=>114, h=>15, t=>$stampText),
					array( p=>6, x=>120, y=>77, w=>152, h=>15, t=>$stampTextD) ); 

	foreach($boxes as $b) 
	{
		$page = $pdf->pages[$b[p]];
		$page->setStyle($style); 
		$page->drawRectangle($b[x], $b[y], $b[x] + $b[w], $b[y] - $b[h]);
		$page->setFillColor($white); 
		$page->drawText($b[t], $b[x] + 1, $b[y] - 11);
		$pdf->pages[$b[p]] = $page;
	}	
}
// 6 Render PDF (Zend_PDF)
$pdf_data = $pdf->render();

// 7. Download PDF stream
downloadPDFfromStream($pdf_data, $display_name, $timestamp);
}
else
{

}
?>