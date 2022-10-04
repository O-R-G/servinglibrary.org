<?php

// $sourceFile = $_REQUEST['sourceFile'];          // no register globals
// $downloadFile = $_REQUEST['downloadFile'];      // no register globals
// $author = $_REQUEST['author'];                  // no register globals
// $issue = $_REQUEST['issue'];                    // no register globals
// $timeStamp = $_REQUEST['timeStamp'];            // no register globals
// $sourceFileStream  = $_REQUEST['sourceFileStream'];            // no register globals


  //////////////////
 //  downloadPDF //
//////////////////

function downloadPDF($sourceFile = null, $downloadFile = null, $timeStamp = null) 
{
	//  Writes document headers to force a PDF download 
	//  Rename the file, add date stamp optional
	//  Must be sent before * any * output to browser
	//  Checks to see if file exists before trying to send it
	//  And dies if not 

	$sourceFile = 'MEDIA/' . $sourceFile . ".pdf";
	if ( !$downloadFile ) $downloadFile = "download";		
	// if ( $timeStamp ) $downloadFile .= " -- " . $timeStamp;
	$downloadFile .= ".pdf";
		
	$contentType = "Content-type: application/pdf";
	$contentDispositionFilename = "Content-Disposition: attachment; filename=" . $downloadFile;

	if(file_exists($sourceFile) && is_file($sourceFile)) {

		header($contentType);
		header($contentDispositionFilename);		
		readfile($sourceFile);
		
	} else {		
		
		echo "I am sorry, there is no valid PDF available to download.";
		exit(0);
	}

	return $true;	
}


//  Writes document headers to force a PDF download 
//  and works from a raw rendered PDF stream
function downloadPDFfromStream($filestream = null, $filename = "download", $timeStamp = null) 
{
	$filename.= ".pdf";
	
	$headers = array();
	$headers[] = "Content-type: application/pdf";
	$headers[] = "Content-Disposition: attachment; filename=" . $filename;

	if($filestream) 
	{
		foreach($headers as $h)
			header($h);	
		echo $filestream;
	} 
	else 
	{			
		echo "I am sorry, there is no valid PDF stream available.";
		exit(0);
	}
	return $true;	
}
?>
