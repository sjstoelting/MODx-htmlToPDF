//<?php
/**
 * Plugin Name: htmlToPDF
 * Description: <strong>0.1.2</strong> Deletes PDF document on changes of the original document
 * Events: onDocFormSave, OnBeforeDocFormDelete
 * 
 * Configuration: $logDeletion=Log the delete event?;list;true,false;true
 * 
 * Deletes PDF documents on changes to the original document.
 *
 * @name htmlToPDF
 *
 * @author Stefanie Janine Stoelting, mail@stefanie-stoelting.de
 * @link https://github.com/sjstoelting/MODx-htmlToPDF
 * @link http://stefanie-stoelting.de/htmltopdf-news.html
 * @link http://www.tcpdf.org/
 * @package htmlToPDF
 * @license LGPL
 * @since 2011/10/20
 * @version 0.1.2
 */
$e = & $modx->event; 

define(PATH_TO_PDF_OUTPUT, 'assets/pdf/');

$logDeletion = (isset($logDeletion) && (strtolower($logDeletion) != 'true' ) && ($logDeletion != '1')) ? false : true;

try {
  $basePath = isset($basePath) && !empty($basePath) ? $basePath : MODX_BASE_PATH;
  if (realpath($basePath) === false)  $basePath = MODX_BASE_PATH;
  
  $outputPdfPath = isset($outputPdfPath) ? $outputPdfPath : PATH_TO_PDF_OUTPUT;
  $outputPdfPath = $basePath . $outputPdfPath;

  if (realpath($outputPdfPath) === false) {
    $modx->logEvent(0, 2, sprintf('The folder %s does not exist!', $outputPdfPath), 'htmlToPDF plugin');
  } else {
    if (!isset($modx->documentObject)) {
      // Generate a document object
      $doc = $modx->getDocument($id, '*', 1);
      if (!empty($doc['alias'])) {
        // If the alias is set, then use the alias, otherwise use the id
        $documentName = $doc['alias'];
      } else {
        $documentName = $id;
      }
    } else {
      $documentName = $modx->documentObject['alias'];
    }
    $documentName = $outputPdfPath . $documentName . '.pdf';
    
    $modx->logEvent(0, 2, '$documentName: ' . $documentName, 'htmlToPDF plugin');

    if (file_exists($documentName)) {
      unlink($documentName);
      if ($logDeletion) {
        $modx->logEvent(0, 2, sprintf('Deleted PDF document: %s', $documentName), 'htmlToPDF plugin');
      }
    }
  }
} catch (Exception $e) {
 $modx->logEvent(0, 3, sprintf('An error occured in line %1$d: %2$s', $e->getLine(), $e->getMessage()), 'htmlToPDF plugin');
}