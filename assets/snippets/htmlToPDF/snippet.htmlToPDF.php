<?php
/**
 * Snippet Name: htmlToPDF
 * Description: <strong>0.1.alpha1</strong>Returns the current document as PDF
 *
 * @name htmlToPDF
 *
 * @author Stefanie Janine Stoelting, mail@stefanie-stoelting.de
 * @link https://github.com/sjstoelting/MODx-htmlToPDF
 * @link http://stefanie-stoelting.de/htmltopdf-news.html
 * @link http://www.tcpdf.org/
 * @package htmlToPDF
 * @license LGPL
 * @since 2011/02/16
 * @version 0.1.alpha1
 * @example [!htmlToPDF? &author=`Stefanie Janine Stoelting` &tvKeywords=`documentTags` &footerPageCaption=`Page` &footerPageSeparator` of ` &headerLogo=`logo.png` &chunkContentFooter=`pdf-contentfooter` &chunkContentHeader=`pdf-header-text` &chunkStyle=`pdf-style`!]
 */

// Encapsulate everything in a try
try {

  $isPDF = isset($_GET['isPDF']) ? $_GET['isPDF'] == 'true' : false;

  if ($isPDF) {
    // Include the rquired files
    require_once(MODX_BASE_PATH . 'assets/lib/tcpdf/config/lang/eng.php');
    require_once(MODX_BASE_PATH . 'assets/lib/tcpdf/tcpdf.php');
    require_once(MODX_BASE_PATH . 'assets/snippets/htmlToPDF/class.htmlToPDF.php');
    require_once(MODX_BASE_PATH . 'assets/snippets/htmlToPDF/class.modxHelper.php');

    // Create new PDF document
    $pdf = new htmlToPDF(
            PDF_PAGE_ORIENTATION,
            PDF_UNIT,
            PDF_PAGE_FORMAT,
            true,
            'UTF-8',
            false
            );

    // Create the MODx helper
    $modxHelper = modxHelper::getInstance();

    // Set document information
    $languageCode = isset($languageCode) ? $languageCode : 'EN';
    $pdf->setDateFormat(isset($dateFormat) ? $dateFormat : 'Y-m-d');
    $marginLeft = isset($marginLeft) && is_numeric($marginLeft) ? $marginLeft : 10;
    $marginRight = isset($marginRight) && is_numeric($marginRight) ? $marginRight : 10;
    $marginTop = isset($marginTop) && is_numeric($marginTop) ? $marginTop : 30;
    $marginBottom = isset($marginBottom) && is_numeric($marginBottom) ? $marginBottom : 25;
    $marginHeader = isset($marginHeader) && is_numeric($marginHeader) ? $marginHeader : 5;
    $marginFooter = isset($marginFooter) && is_numeric($marginFooter) ? $marginFooter : 10;
    $pdf->setHeaderFontType(isset($headerFontType) ? $headerFontType : htmlToPDF::DEFAULT_FONT_TYPE);
    $pdf->setHeaderFontSize(isset($headerFontSize) && is_numeric($headerFontSize) ? $headerFontSize : htmlToPDF::DEFAULT_HEADER_FONT_SIZE);
    $pdf->setImageFile(isset($headerLogo) ? $headerLogo : '');
    $pdf->setHeaderFontBold(isset($headerFontBold) && is_bool($headerFontBold) ? $headerFontBold : true);
    $footerPositionFromBottom = isset($footerPositionFromBottom) && is_numeric($footerPositionFromBottom) ? $footerPositionFromBottom : htmlToPDF::DEFAULT_FOOTER_POSITION_FROM_BOTTOM;
    $pdf->setFooterFontType($footerFontType = isset($footerFontType) ? $footerFontType : htmlToPDF::DEFAULT_FONT_TYPE);
    $pdf->setFooterFontItalic(isset($footerFontItalic) && is_bool($footerFontItalic) ? $footerFontItalic : true);
    $pdf->setFooterFontSize(isset($footerFontSize) && is_numeric($footerFontSize) ? $footerFontSize : htmlToPDF::DEFAULT_FOOTER_FONT_SIZE);
    $footerPageCaption = isset($footerPageCaption) ? $footerPageCaption : '';
    $footerPageSeparator = isset($footerPageSeparator) ? $footerPageSeparator : ' ';
    $contentFontType = isset($contentFontType) ? $contentFontType : 'times';
    $contentFontSize = isset($contentFontSize) && is_numeric($contentFontSize) ? $contentFontSize : 10;
    $pdf->setLongTitleAboveContent(isset($longTitleAboveContent) ? $longTitleAboveContent == 1 : true);
    $pdf->setStripCSSFromContent(isset($stripCSSFromContent) ? $stripCSSFromContent == 1 : true);
    $rewritePDF = isset($rewritePDF) ? $rewritePDF == 1 : true;

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($author);
    $pdf->SetTitle($modx->documentObject['pagetitle']);
    $pdf->SetSubject($modx->documentObject['longtitle']);
    $pdf->SetKeywords($tvKeywords = isset($tvKeywords) ? $tvKeywords : '');

    // Set the chunk contents
    $pdf->setHeaderText(isset($chunkContentHeader) ? $chunkContentHeader : '');
    $pdf->setContentFooter(isset($chunkContentFooter) ? $chunkContentFooter : '');
    $pdf->setCSS(isset($chunkStyle) ? $chunkStyle : '');

    // Set the content
    $pdf->setContent(
            isset($chunkContent) ? !empty($chunkContent) : false,
            isset($chunkContent) ? $chunkContent : '');

    // Set the header data
    $pdf->SetHeaderData();

    // Set header and footer fonts
    $pdf->setHeaderFont(
            Array(
              $pdf->getHeaderFontType(),
              //$pdf->getHeaderFontBold(),
              '',
              $pdf->getHeaderFontSize()
            ));
    $pdf->setFooterFont(
            Array(
              $pdf->getFooterFontType(),
              //$pdf->getFooterFontItalic(),
              '',
              $pdf->getFooterFontSize()
            ));

    // Set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins
    $pdf->SetMargins($marginLeft, $marginTop, $marginRight);
    $pdf->SetHeaderMargin($marginHeader);
    $pdf->SetFooterMargin($marginFooter);

    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, $marginBottom);

    // Set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // Set some language-dependent strings
    $pdf->setLanguageArray($languageCode);

    // Set font
    if ($pdf->useCSS()) {
      $pdf->SetFont($contentFontType, '', $contentFontSize);
    }

    // Add a page
    $pdf->AddPage();

    // Output the content
    $pdf->writeHTML($pdf->getContent(), true, false, true, false, '');

    // Reset pointer to the last page
    $pdf->lastPage();

    // Close and output PDF document
    $documentName = 'assets/pdf/' .$modx->documentObject['alias'] . '.pdf';
    if (!file_exists($modx->config['base_path'] . $documentName)) {
      $pdf->Output($modx->config['base_path'] . $documentName, 'F');
    } elseif ($rewritePDF) {

      $pdf->Output($modx->config['base_path'] . $documentName, 'F');
    }

    // Relocate to the PDF document
    header("Location: /$documentName");
  }

// Catch all exceptions and log them into the MODx event log
} catch (Exception $e) {

  $modx->logEvent(0, 2, sprintf('An error occured in line %1$d: %2$s', $e->getLine(), $e->getMessage()), 'htmlToPDF snippet');

  // Exeption is only shown, on backend login
  if (isset( $_SESSION['mgrValidated']) ? $_SESSION['mgrValidated'] : false == 1) {

    $modx->messageQuit(sprintf('An error occured in file %1$s in line %2$d: %3$s code: %4s', $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode()));

  }
}
?>