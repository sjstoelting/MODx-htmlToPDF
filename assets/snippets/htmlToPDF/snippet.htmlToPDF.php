<?php
/**
 * Snippet Name: htmlToPDF
 * Description: <strong>0.1</strong> Returns the current document as PDF
 *
 * @name htmlToPDF
 *
 * @author Stefanie Janine Stoelting, mail@stefanie-stoelting.de
 * @link https://github.com/sjstoelting/MODx-htmlToPDF
 * @link http://stefanie-stoelting.de/htmltopdf-news.html
 * @link http://www.tcpdf.org/
 * @package htmlToPDF
 * @license LGPL
 * @since 2011/02/19
 * @version 0.1
 * @example [!htmlToPDF? &author=`Stefanie Janine Stoelting` &tvKeywords=`documentTags` &headerLogo=`logo.png` &chunkContentFooter=`pdf-contentfooter` &chunkStandardHeader=`pdf-header-text` &chunkStyle=`pdf-style`!]
 */

// Encapsulate everything in a try
try {

  $isPDF = isset($_GET['isPDF']) ? $_GET['isPDF'] == 'true' : false;

  if ($isPDF) {
    define(PATH_TO_SNIPPET, 'assets/snippets/htmlToPDF/');
    define(PATH_TO_TCPDF, 'assets/lib/tcpdf/');
    define(PATH_TO_PDF_OUTPUT, 'assets/pdf/');

    // Check, whether to use MODX_BASE_PATH or to use a defined real path
    $basePath = isset($basePath) && !empty($basePath) ? $basePath : MODX_BASE_PATH;
    if (realpath($basePath) === false)  $basePath = MODX_BASE_PATH;

    // Get the path informations, if a path is not found, the default path
    // is used
    $htmlToPdfPath = isset($htmlToPdfPath) ? $htmlToPdfPath : PATH_TO_SNIPPET;
    if (realpath($basePath . $htmlToPdfPath) === FALSE) $htmlToPdfPath = PATH_TO_SNIPPET;

    $tcpdfPath = isset($tcpdfPath) ? $tcpdfPath : PATH_TO_TCPDF;
    if (realpath($basePath . $tcpdfPath) === FALSE) $tcpdfPath = PATH_TO_TCPDF;

    $outputPdfPath = isset($outputPdfPath) ? $outputPdfPath : PATH_TO_PDF_OUTPUT;
    if (realpath($basePath . $outputPdfPath) === FALSE) $outputPdfPath = PATH_TO_PDF_OUTPUT;

    // Include the rquired files
    require_once($basePath . $tcpdfPath . 'config/lang/eng.php');
    require_once($basePath . $tcpdfPath . 'tcpdf.php');
    require_once($basePath . $htmlToPdfPath . 'class.htmlToPDF.php');
    require_once($basePath . $htmlToPdfPath . 'class.modxHelper.php');

    // Get the footer properties before the creation of the PDF object, because
    // it is not possible to set them after the creation.
    $footerFontType = isset($footerFontType) ? $footerFontType : htmlToPDF::DEFAULT_FONT_TYPE;
    $footerFontItalic =isset($footerFontItalic) ? $footerFontItalic == 1: true;
    $footerFontSize = isset($footerFontSize) && is_numeric($footerFontSize) ? $footerFontSize : htmlToPDF::DEFAULT_FOOTER_FONT_SIZE;
    $footerChunk = isset($footerChunk) ? $footerChunk : '';
    $footerPositionFromBottom = isset($footerPositionFromBottom) && is_numeric($footerPositionFromBottom) ? $footerPositionFromBottom : htmlToPDF::DEFAULT_FOOTER_POSITION_FROM_BOTTOM;
    $headerImageHeight = isset($headerImageHeight) && is_numeric($headerImageHeight) ? $headerImageHeight : 20;

    // Create new PDF document

    $pdf = new htmlToPDF(
            $footerChunk,
            $footerFontType,
            $footerFontItalic,
            $footerFontSize,
            $footerPositionFromBottom,
            $headerImageHeight);

    // Create the MODX helper
    $modxHelper = modxHelper::getInstance();

    // Set document information
    $pdf->setLanguageArray(isset($languageCode) ? $languageCode : 'EN');
    $pdf->setDateFormat(isset($dateFormat) ? $dateFormat : 'Y-m-d');
    $marginLeft = isset($marginLeft) && is_numeric($marginLeft) ? $marginLeft : 10;
    $marginRight = isset($marginRight) && is_numeric($marginRight) ? $marginRight : 10;
    $marginTop = isset($marginTop) && is_numeric($marginTop) ? $marginTop : 30;
    $pdf->SetAutoPageBreak(TRUE, isset($marginBottom) && is_numeric($marginBottom) ? $marginBottom : 25);
    $pdf->SetHeaderMargin(isset($marginHeader) && is_numeric($marginHeader) ? $marginHeader : 5);
    $pdf->SetFooterMargin(isset($marginFooter) && is_numeric($marginFooter) ? $marginFooter : 10);
    $pdf->setHeaderFontType(isset($headerFontType) ? $headerFontType : htmlToPDF::DEFAULT_FONT_TYPE);
    $pdf->setHeaderFontSize(isset($headerFontSize) && is_numeric($headerFontSize) ? $headerFontSize : htmlToPDF::DEFAULT_HEADER_FONT_SIZE);
    $pdf->setImageFile(isset($headerLogo) ? $basePath . $tcpdfPath : '', isset($headerLogo) ? $headerLogo : '');

    $pdf->setHeaderFontBold(isset($headerFontBold) ? $headerFontBold == 1 : true);
    $contentFontType = isset($contentFontType) ? $contentFontType : 'times';
    $contentFontSize = isset($contentFontSize) && is_numeric($contentFontSize) ? $contentFontSize : 10;
    $pdf->setLongTitleAboveContent(isset($longTitleAboveContent) ? $longTitleAboveContent == 1 : true);
    $pdf->setStripCSSFromContent(isset($stripCSSFromContent) ? $stripCSSFromContent == 1 : true);
    $pdf->setRewritePDF(isset($rewritePDF) ? $rewritePDF == 1 : true);
    $pdf->SetDefaultMonospacedFont(isset($fontMonoSpaced) ? $fontMonoSpaced : PDF_FONT_MONOSPACED);
    $pdf->setImageScale(isset($imageScaleRatio) ? $imageScaleRatio : PDF_IMAGE_SCALE_RATIO);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor(isset($author) ? $author : '');
    $pdf->SetTitle($modx->documentObject['pagetitle']);
    $pdf->SetSubject($modx->documentObject['longtitle']);
    $pdf->SetKeywords($tvKeywords = isset($tvKeywords) ? $tvKeywords : '');

    // Set the chunk contents
    $pdf->setHeaderText(isset($chunkStandardHeader) ? $chunkStandardHeader : '');
    $pdf->setContentFooter(isset($chunkContentFooter) ? $chunkContentFooter : '');
    $pdf->setCSS(isset($chunkStyle) ? $chunkStyle : '');

    // Set the content
    $pdf->setContent(
            isset($chunkContent) ? !empty($chunkContent) : false,
            isset($chunkContent) ? $chunkContent : '');

    // Set the header data
    $pdf->SetHeaderData($basePath . $tcpdfPath);

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

    // Set margins
    $pdf->SetMargins($marginLeft, $marginTop, $marginRight);

    // Set font
    if ($pdf->useCSS()) {
      $pdf->SetFont($contentFontType, '', $contentFontSize);
    }

    $pdf->generatePDF($basePath, $outputPdfPath);
  }

// Catch all exceptions and log them into the MODX event log
} catch (Exception $e) {

  $modx->logEvent(0, 2, sprintf('An error occured in line %1$d: %2$s', $e->getLine(), $e->getMessage()), 'htmlToPDF snippet');

  // Exeption is only shown, on backend login
  if (isset( $_SESSION['mgrValidated']) ? $_SESSION['mgrValidated'] : false == 1) {

    $modx->messageQuit(sprintf('An error occured in file %1$s in line %2$d: %3$s code: %4s', $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode()));

  }
}
?>