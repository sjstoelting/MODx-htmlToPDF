<?php
/**
 * Handles PDF header and footer options, extends the TCPDF class-
 *
 * @name htmlToPDF
 *
 * @author Stefanie Janine Stoelting, mail@stefanie-stoelting.de
 * @link https://github.com/sjstoelting/MODx-htmlToPDF
 * @link http://stefanie-stoelting.de/htmltopdf-news.html
 * @link http://www.tcpdf.org/
 * @package htmlToPDF
 * @license LGPL
 * @since 2011/10/18
 * @version 0.1.2
 */
final class modxHelper {
  /**
   * Contains the instance of modxHelper
   * @var modxHelper
   */
  private static $_instance = NULL;


  /**
   * A private constructor, the class will only be instantiated by itself.
   */
  private function  __construct()
  {
  } // __construct

  /**
   * Disallow clone from outside.
   */
  private function  __clone()
  {
  } // __clone

  /**
   * Returns the instance, if the instance is not created by now, the instance
   * ic created.
   *
   * @return modxHelper The modxHelper object
   */
  public static function getInstance()
  {
    if (self::$_instance === NULL) {
      self::$_instance = new self;
    }
    
    return self::$_instance;
  } // getInstance

  /**
   * Returns the content with the links placed in the content with [~id~].
   * The original code is part of document.parser.class.inc of MODX Evolution.
   * The extension to the original code is to set the site URL as a prefix to
   * use the links outsite of the current domain, for example in PDF files.
   *
   * @global object $modx
   * @param string $documentSource The document content source
   * @return string The content with replaced URLs
   */
  public function rewriteUrls($documentSource)
  {
    global $modx;

    // rewrite the urls
    if ($modx->config['friendly_urls'] == 1) {
      $aliases= array ();
      foreach ($modx->aliasListing as $item) {
        $aliases[$item['id']]= (strlen($item['path']) > 0 ? $item['path'] . '/' : '') . $item['alias'];
      }
      $in= '!\[\~([0-9]+)\~\]!ise'; // Use preg_replace with /e to make it evaluate PHP
      $isfriendly= ($modx->config['friendly_alias_urls'] == 1 ? 1 : 0);
      $pref= $modx->getConfig('site_url') .  $modx->config['friendly_url_prefix'];
      $suff= $modx->config['friendly_url_suffix'];
      $thealias= '$aliases[\\1]';
      $found_friendlyurl= "\$modx->makeFriendlyURL('$pref','$suff',$thealias)";
      $not_found_friendlyurl= "\$modx->makeFriendlyURL('$pref','$suff','" . '\\1' . "')";
      $out= "({$isfriendly} && isset({$thealias}) ? {$found_friendlyurl} : {$not_found_friendlyurl})";
      $documentSource= preg_replace($in, $out, $documentSource);
    } else {
      $in= '!\[\~([0-9]+)\~\]!is';
      $out= $modx->getConfig('site_url') . "index.php?id=" . '\1';
      $documentSource= preg_replace($in, $out, $documentSource);
    }
    return $documentSource;
  } // rewriteUrls

  /**
   * Removes inline CSS from HTML content.
   *
   * @param string $content The HTML content that may contain inline CSS
   * @return string The content without inline CSS
   */
  public function removeInlineCSS($content)
  {
    return preg_replace('#style=("|\')(.*?)("|\')#', '', $content);
  } // removeInlineCSS

  /**
   * Removes YAMS tags from  HTML content.
   *
   * @param string $content The HTML content
   * @return string The content without YAMS tags
   */
  
  public function removeYAMSTags($content)
  {
    $content = preg_replace('#\(/?yams[^\)]+\)#', '', $content);
    return preg_replace('#\(lang:[^\)]+\)#', '', $content);
  } // removeYAMSTags
    
} // modxHelper