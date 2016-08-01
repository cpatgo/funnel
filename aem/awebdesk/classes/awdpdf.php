<?php

require_once(awebdesk('ezpdf/class.ezpdf.php'));


class ACPDFBuilder extends Cezpdf {
	var $reportContents = array();
	var $font = './awebdesk/ezpdf/fonts/Courier.afm';

	function ACPDFBuilder($p,$o){
		$this->font = dirname(dirname(__FILE__)) . '/ezpdf/fonts/Courier.afm';
		$this->Cezpdf($p,$o);
	}

	function init(){
		$this->ezSetMargins(50,70,50,50);

		// Set up the courier font family
		$fontarray = array(
			'b'=>'Courier-Bold.afm',
			'i'=>'Courier-Oblique.afm',
			'bi'=>'Courier-BoldOblique.afm',
			'ib'=>'Courier-BoldOblique.afm'
		);
		$this->setFontFamily('Courier.afm', $fontarray);

		// select a font
		$this->selectFont($this->font);

		// put a line top and bottom on all the pages
		$all = $this->openObject();
		$this->saveState();
		$this->setStrokeColor(0,0,0,1);
		$this->line(20,40,592,40);
		$this->line(20,772,592,772);
		//$this->ezImage(dirname(__FILE__) . '/logo.jpg', 0, 0, "none", 'left');
		if ( isset($GLOBALS['adesk_pdf_copyright']) ) {
			$this->addText(50,28,6, iconv(strtoupper(smarty_modifier_i18n("utf-8")), "WINDOWS-1252//IGNORE", $GLOBALS['adesk_pdf_copyright']));
		}
		$this->restoreState();
		$this->closeObject();
		// note that object can be told to appear on just odd or even pages by changing 'all' to 'odd'
		// or 'even'.
		$this->addObject($all,'all');


		//$pdf->openHere("Fit");

		$this->ezStartPageNumbers(550,28,10,'','',1);
	}

	// Callback method for tracking title links for the TOC
	function toc($info) {
		// this callback records all of the table of contents entries, it also places a destination marker there
		// so that it can be linked too
		$input = explode("|", $info['p']);
		$level = $input[0];
		$catId = $input[1];
		$label = rawurldecode($input[2]);
		$type = $input[3];
		// Get the page number, the one that is displayed on the bottom of the page, not the actual page
		$pageNum = $this->ezWhatPageNumber($this->ezGetCurrentPageNumber());
		// Generate an anchor name
		$anchor = "toc" . $catId;
		// Track it
		$this->reportContents[] = array("label" => $label, "pagenum" => $pageNum, "level" => $level, "anchor" => $anchor, "type" => $type );
		// Add a destination
		$this->addDestination($anchor, 'FitH', $info['y'] + $info['height']);
	}

	// Callback method to print dots
	function dots($info){
		// draw a dotted line over to the right and put on a page number
		$lbl = $info['p'];
		$xpos = 520;
		$size=12;
		$thick=0.75;

		$this->saveState();
		$this->setLineStyle($thick,'round','',array(0,10));
		$this->line($xpos,$info['y'],$info['x']+5,$info['y']);
		$this->restoreState();
		$this->addText($xpos+5,$info['y'],$size,$lbl);
	}

	// This function takes a specific array structure
	// See the code below
	function printCategoryTree( $categoryNode, $level = 0 ) {
		// Print out the category heading at the proper level
		if(!isset($categoryNode['name'])) {
			if(!isset($categoryNode['title'])) {
				$categoryNode['name'] = $categoryNode['title'];
			} else {
				$categoryNode['name'] = ''; // "Top Level Category"
			}
		}
		if(!isset($categoryNode['desc'])) {
			if(!isset($categoryNode['descript'])) {
				$categoryNode['desc'] = $categoryNode['descript'];
			} elseif(!isset($categoryNode['description'])) {
				$categoryNode['desc'] = $categoryNode['description'];
			} else {
				$categoryNode['desc'] = ''; // "Parent category to all other categories"
			}
		}
		$catInfo = $categoryNode;
		$catInfo['name'] = iconv(strtoupper(smarty_modifier_i18n("utf-8")), "WINDOWS-1252//IGNORE", $categoryNode['name']);
		$catInfo['desc'] = iconv(strtoupper(smarty_modifier_i18n("utf-8")), "WINDOWS-1252//IGNORE", $categoryNode['desc']);

		$indentLevel = (($level - 1) < 0) ? 0 : $level - 1;
		$indentLevel = $indentLevel * 15;

		// Only if it's not the root category do we print out a header
		if($catInfo['id'] != "root"){
			$ok = false;
			$this->transaction("start");
			while(!$ok){
				$thisPageNum = $this->ezPageCount;

				// Black border
				$this->saveState();
				$this->setColor(0,0,0);
				$x = $this->ez['leftMargin'] - 1 + $indentLevel;
				$y = $this->y-$this->getFontHeight(18)-$this->getFontHeight(12)+$this->getFontDecender(18)+$this->getFontDecender(12) - 1 + 3;
				$width = $this->ez['pageWidth'] - $this->ez['leftMargin'] - $this->ez['rightMargin'] + 2 - ($level * 15);
				$height = $this->getFontHeight(18) + $this->getFontHeight(12) + 2;
				//echo $this->getFontHeight(18);
				$this->filledRectangle($x, $y, $width, $height);
				$this->restoreState();

				$this->saveState();
				$this->setColor(0.85,0.9,1.0);
				$x = $this->ez['leftMargin'] + $indentLevel;
				$y = $this->y-$this->getFontHeight(18)-$this->getFontHeight(12)+$this->getFontDecender(18)+$this->getFontDecender(12) + 3;
				$width = $this->ez['pageWidth'] - $this->ez['leftMargin'] - $this->ez['rightMargin'] - ($level * 15);
				$height = $this->getFontHeight(18) + $this->getFontHeight(12);
				$this->filledRectangle($x, $y, $width, $height);
				$this->restoreState();

				$header = $catInfo['name'] . "<C:toc:$level|" . $catInfo['id'] . "|" . rawurlencode($catInfo['name']) . "|c>";
				// Category title
				$this->ezText($header, 18, array('justification'=>'center'));
				// Category desc
				$this->ezText($catInfo['desc'], 12, array('justification'=>'center'));

				$this->ezText("\n\n", 10);

				if ($this->ezPageCount == $thisPageNum){
					$this->transaction('commit');
					$ok = true;
				} else {
					// then we have moved onto a new page, bad bad, as the background colour will be on the old one
					$this->transaction('rewind');
					$this->ezNewPage();
				}
			}
		}

		if(count($categoryNode['articles']) > 0) {
			// For each article
			foreach($categoryNode['articles'] as $articleId) {
				$this->ezSetMargins(50,70,50 + $indentLevel,50);
				// Get the article
				$article = get_article($articleId);
                                $article['question'] = iconv(strtoupper(smarty_modifier_i18n("utf-8")), "WINDOWS-1252//IGNORE", $article['question']);
                                $article['content']  = iconv(strtoupper(smarty_modifier_i18n("utf-8")), "WINDOWS-1252//IGNORE", $article['content']);
				// Print out the question in a header
				$header =
					"<b>" . $article['question'] . "</b><C:toc:" . ($level + 1) . "|article" . $article['id'] . "|" . rawurlencode($article['question']) . "|a>\n";
				$this->ezText($header, 12, array('justification' => 'left', 'leading' => 18));
				$contentArray = array();
				$content = $article['content'];

				// Print out the text
				$content = str_replace('&euro;', '�', $content);
				$content = str_replace('&trade;', '�', $content);
				$content = html_entity_decode($content);
				$content = preg_replace("/<br\s*\/?>/i", "\n", $content);
				$content = preg_replace("/<p\s*\/?>/i", "\n", $content);
				$content = preg_replace("/<\/p\s*>/i", "", $content);
				$content = preg_replace("/(\r?\n)*\s*\[-\s*PAGE\s*.*-]\s*(\r?\n)*/", "", $content);
				$content = preg_replace("/\r\n/", "\n", $content);
				$content = trim(strip_tags($content, "<b><i><u><img>"));
				//$content = strip_tags($content);
				// Find images in the text
				//$content = preg_replace_callback("/<img src=[\"'](.*)[\"'][^>]*>/",
				//	create_function('$matches','return "<C:image:" . rawurlencode($matches[1]) . ">";'), $content);

				// Find the image tags
				preg_match_all("/<img[^>]*src=[\"'](.*)[\"'][^>]*>/U", $content, $matches);
				$images = array();
				$tagsToReplace = array();
				// get the image URLs in order and save them
				foreach($matches[1] as $match){
					$images[] = $match;
				}
				// Get the <img> tags and save them to replace
				foreach($matches[0] as $match) {
					$tagsToReplace[] = $match;
				}
				// Replace all image tags with placeholders
				$content = str_replace($tagsToReplace, "<--replacedimage-->", $content);
				// Split the contenton those
				$contentArray = explode("<--replacedimage-->", $content);
				$currentImage = 0;
				if(count($contentArray) == 1){
					$this->ezText($content . "\n\n", 10, array('justification' => 'left', 'leading' => 10));
				} else {
					$imageIdx = 0;
					foreach($contentArray as $chunk) {
						$this->ezText($chunk . "\n", 10, array('justification' => 'left', 'leading' => 10));
						$ok = false;
						$this->transaction("start");
						while(!$ok){
							$thisPageNum = $this->ezPageCount;
							$this->ezSetMargins(50,70,0,0);
							$this->ezImage($images[$imageIdx], 5, 0, "none");
							$this->ezSetMargins(50,70,50 + $indentLevel,50);
							if ($this->ezPageCount == $thisPageNum){
								$this->transaction('commit');
								$ok = true;
							} else {
								// then we have moved onto a new page, bad bad, as the background colour will be on the old one
								$this->transaction('rewind');
								$this->ezNewPage();
							}
						}
						$this->ezText("\n", 10, array('justification' => 'left'));
						// Move on to the next image
						$imageIdx++;
					}
					$this->ezText("\n", 10, array('justification' => 'left'));
				}
			}
		} else {
			/*
			if($catInfo['id'] != "root"){
				$this->ezSetMargins(50,70,50 + $indentLevel,50);
				$this->ezText(
					'<i>' .
					iconv(
						strtoupper(smarty_modifier_i18n("utf-8")),
						"WINDOWS-1252//IGNORE",
						'' // "There are no articles in this category."
					) .
					'</i>',
					12,
					array('justification' => 'left')
				);
			}
			*/
		}

		$this->ezSetMargins(50,70,50,50);

		// Print out the child categories with level + 1
		foreach($categoryNode['children'] as $child){
			$this->printCategoryTree( $child, $level + 1 );
		}

		// Stream the output if we reach this point in the top level
		if($level == 0) {
			$this->ezStopPageNumbers(1,1);
			$this->buildCover($catInfo['name'], $catInfo['desc']);
			$this->buildToc();
			$this->ezStream(array("Content-Disposition" => $catInfo['name'] . ".pdf"));
		}
	}

	function buildToc() {
		// now add the table of contents, including internal links
		$this->ezInsertMode(1,1,'before');
		$this->ezNewPage();
		$this->ezText("Contents\n",26,array('justification'=>'centre'));
		$xpos = 520;
		$contents = $this->reportContents;
		foreach($contents as $k=>$v){
			if($v['type'] == "c") {
				$this->ezText('<c:ilink:'.$v['anchor'].'><b>' . $v['label'] . '</b></c:ilink><C:dots:'.$v['pagenum'].'>',
					14, array('left' => ($v['level'] * 20), 'aright'=>$xpos));
			} else {
				$this->ezText('<c:ilink:'.$v['anchor'].'><i>' . $v['label'] . '</i></c:ilink><C:dots:'.$v['pagenum'].'>',
					10, array('left' => ($v['level'] * 20), 'aright'=>$xpos));
			}
		}
	}

	function buildCover($categoryName, $categoryDesc) {
		$globalopts = adesk_site_get();
		// now add the Cover
		$this->ezInsertMode(1,1,'before');
		$this->ezNewPage();

		$this->ezSetDy(-200);
		$this->ezText($globalopts['title'] . "\n\n", 36, array("justification" => "centre"));
		if($categoryName != "") {
			$this->ezText("$categoryName Category", 18, array("justification" => "centre"));
		}
		if($categoryDesc != "") {
			$this->ezText("<i>$categoryDesc</i>\n", 14, array("justification" => "centre"));
		}
	}


// overloads
// ------------------------------------------------------------------------------

	function ezImage($image,$pad = 5,$width = 0,$resize = 'full',$just = 'center',$border = ''){
		//beta ezimage function
		$temp = false;
		if (stristr($image,'://'))//copy to temp file
		{
			// OUR CODE START
			if ( !function_exists('adesk_http_get') ) require_once(dirname(dirname(__FILE__)) . '/functions/http.php');
			$cont = adesk_http_get($image);
			/*
			$fp = @fopen($image,"rb");
			while(!feof($fp))
			{
				$cont.= fread($fp,1024);
			}
			fclose($fp);
			*/
			// OUR CODE END
			$image = tempnam ("/tmp", "php-pdf");
			$fp2 = @fopen($image,"w");
			fwrite($fp2,$cont);
			fclose($fp2);
			$temp = true;
		}
		$r = parent::ezImage($image, $pad, $width, $resize, $just, $border);
		if ($temp == true) unlink($image);
		return $r;
	}
// ------------------------------------------------------------------------------

}

?>
