<?php 

	if ( $pageName != "pop") {	
	
		if ( $pageName != "time") {	
	
			// Badge
	
			$html  = "<div id='badge' class='badgeContainer'>";
			if ( $pageName == 'index') {
		
				$html .= "<a href='words.html?id=96'><img src='MEDIA/TSL-red.gif' style='width: 100%;' alt='badge' /></a>";		
				
				// tool tip
				// $html .= "<a class='tooltip body' href='words.html?id=96'><span>THE SERVING LIBRARY is . . .</span><img src='MEDIA/TSL-red.gif' style='width: 100%;' alt='badge' /></a>";
			
			} else {
		
				$html .= "<a href='index.html'><img src='MEDIA/TSL-red.gif' style='width: 100%;' alt='badge' /></a>";	
			}
			$html .= "</div>";

			echo $html;

		} else {

			// Clock
	
			$html  = "<div id='name' class='clockContainer'>";
			$html  .= "<a href='index.html'>";
			$html  .= "<canvas datasrc='_Processing/TheServingLibrary.pde' width='200' height='200'></canvas>";
			$html  .= "</a>";
			$html  .= "</div>";

			echo $html;
		}
	
		if ( $pageName != 'words' ) {

			$html  = "<div id='Menu' class='TSLContainer body'>";
			
			if (( $pageName == 'index' ) && ( !$dev )) {
			
                                $html .= "<a href='about'>About</a> / <a href='subscribe'>Subscribe</a> / <a href='journal'>Journal</a>";				
			}
			
			if ( $dev == 1 ) {

				$html .= "<a href='join'>Join</a> / <a href='subscribe'>Subscribe</a> / <a href='about'>About</a> / <a href='journal'>Journal</a>"; 

			} else if ( $dev == 2 ) {

				$html .= "<a href='about'>About</a> / <a href='subscribe'>Subscribe</a>"; 

			} else if ( $dev == 3 ) {

				$html .= "<a href='journal'>Journal</a> / <a href='subscribe'>Subscribe</a> / <a href='about'>About</a>"; 
			}
			
			$html .= "</div>";			
			echo $html;
		}
	}
	
?>

	</body>
</html>
