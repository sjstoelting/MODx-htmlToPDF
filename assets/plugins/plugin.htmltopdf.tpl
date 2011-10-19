<?php
/**
 * Plugin Name: htmlToPDF
 * Description: <strong>0.1.2</strong> Deletes PDF document on changes of the original document
 * Events: onDocFormSave, OnDocFormDelete
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
 * @since 2011/10/19
 * @version 0.1.2
 */

define(PATH_TO_PDF_OUTPUT, 'assets/pdf/');
$outputPdfPath = isset($outputPdfPath) ? $outputPdfPath : PATH_TO_PDF_OUTPUT;

$documentName = $outputPdfPath .$modx->documentObject['alias'] . '.pdf';

if (!file_exists($basePath . $documentName)) {
    unlink($basePath . $documentName);
}
