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
 * @since 2012/03/26
 * @version 0.1.3.2
 */
class htmlToPDF extends TCPDF {
  /**
   * Constant string The default header font type.
   */
  const DEFAULT_FONT_TYPE = 'helvetica';

  /**
   * Constant int The default header font size.
   */
  const DEFAULT_HEADER_FONT_SIZE = 16;

  /**
   * Constant int The default footer font size.
   */
  const DEFAULT_FOOTER_FONT_SIZE = 8;

  /**
   * Constant int The default position of the footer from the bottom.
   */
  const DEFAULT_FOOTER_POSITION_FROM_BOTTOM = -15;

  /**
   * Constant string The default footer content.
   */
  const DEFAULT_PAGE_FOOTER_CONTENT = 'Page %1s / %2s';

  /**
   * Constant string The default date format.
   */
  const DEFAULT_DATE_FORMAT = 'Y-m-d';

  /**
   * Constant string The path to the TCPDF image folder.
   */
  const TCPDF_IMAGE_FOLDER = 'images/';

  /**
   * Constant int The default height for header images in mm, default is 20.
   */
  const DEFAULT_HEADER_IMAGE_HEIGHT = 20;
  
  /**
   * Constant string The compatible version number of TCPDF 
   */
  const TCPDF_VERSION = '5.9.152';

  /**
   * The path and name of the image file.
   * @var string
   */
  private $_imageFile = '';

  /**
   * The file type of the image, only jpg, gif, or png are allowed.
   * @var string
   */
  private $_imageFileType = '';

  /**
   * The header text for the PDF file.
   * @var string
   */
  private $_headerText = '';

  /**
   * The font type for the header, default is helvetica.
   * @var string
   */
  private $_headerFontType;

  /**
   * The font size for the header text, default is 20.
   * @var int
   */
  private $_headerFontSize;

  /**
   * Whether the header text is bold, or not, default is true.
   * @var string
   */
  private $_headerFontBold = 'B';

  /**
   * The font type for the footer, default is helvetica.
   * @var string
   */
  private $_footerFontType;

  /**
   * The font size for the footer, default is 8.
   * @var int
   */
  private $_footerFontSize;

  /**
   * Whether the footer text is italic, or not, default is true.
   * @var string
   */
  private $_footerFontItalic = 'I';

  /**
   * The caption text for 'page', default is english page.
   * @var string
   */
  private $_footerPageCaption = 'Page';

  /**
   * The page separator for page ../.., default is /.
   * @var string
   */
  private $_footerPageSeparator = '/';

  /**
   * The footer postion from the bottom of the page in mm, default is 15 mm.
   * @var int
   */
  private $_footerPositionFromBottom;

  /**
   * Contains the keywords for the document.
   * @var string
   */
  private $_keyWords = '';

  /**
   * Contains the CSS styles for the PDF documents
   * @var string
   */
  private $_cssStyle = '';

  /**
   * Contains the document content
   * @var string
   */
  private $_content = '';

  /**
   * Contains the date format string, default is Y-m-d.
   * @var string
   */
  private $_dateFormat;

  /**
   * Contains the information about to strip the CSS inline tags, or not. 
   * Default is true.
   * @var boolean
   */
  private $_stripCSSFromContent = true;

  /**
   * Whether the long title should be above the content, or not. Does only work
   * with $sourceIsChunk = false in setContent. Default is true.
   * @var boolean
   */
  private $_longTitleAboveContent = true;

  /**
   * Contains the information about whether to overwrite PDF files, if they
   * already exists, or not. Default is true.
   * @var boolean
   */
  private $_rewritePDF = true;

  /**
   * The footer text with placeholders for variables, default is
   * 'Page $d1 / $d2'.
   * @var string
   */
  private $_footerContent;

  /**
   * The height of the header image in mm, default is 20.
   * @var int
   */
  private $_headerImageHeight;

  /**
   * YAMS id - (yams_id) will be replaced in chunks by this value.
   * @var int
   */
  private $_yamsId = '';

  /**
   * Constructor, overwrites the TCPDF constructor to set some properties, that
   * only can be set before the TCPDF constructor is called. In addition,
   * several default values for properties are set.
   *
   * @param string $footerChunk The name of the footer chunk.
   * @param string $footerFont The font for the footer.
   * @param boolean $footerFontItalic Whether the footer font should be italic,
   *                or not.
   * @param int $footerFontSize The footer font size.
   * @param int $footerPositionFromBottom The footer position from bottom in mm,
   *            this value should be negative.
   * @param int $headerImageHeight The height of the header image in mm, default
   *            is 20.
   */
  public function  __construct($footerChunk, $footerFont, $footerFontItalic,
          $footerFontSize, $footerPositionFromBottom,
          $headerImageHeight=self::DEFAULT_HEADER_IMAGE_HEIGHT)
  {
    // Set default values with constants
    $this->_headerFontType = self::DEFAULT_FONT_TYPE;
    $this->_headerFontSize = self::DEFAULT_HEADER_FONT_SIZE;
    $this->_footerFontType = self::DEFAULT_FONT_TYPE;
    $this->_footerFontSize = self::DEFAULT_FOOTER_FONT_SIZE;
    $this->_footerPositionFromBottom = self::DEFAULT_FOOTER_POSITION_FROM_BOTTOM;
    $this->_dateFormat = self::DEFAULT_DATE_FORMAT;
    $this->_footerContent = self::DEFAULT_PAGE_FOOTER_CONTENT;

    // Set the footer option before calling the parent constructor, otherwise
    // it is not possible, to set the footer flexible
    $this->setFooterFontType($footerFont);
    $this->setFooterFontSize($footerFontSize);
    $this->setFooterFontItalic($footerFontItalic);
    $this->setFooterPositionFromBottom($footerPositionFromBottom);
    $this->setFooterContent($footerChunk);
    $this->setHeaderImageHeight($headerImageHeight);

    parent::__construct(
            PDF_PAGE_ORIENTATION,
            PDF_UNIT,
            PDF_PAGE_FORMAT,
            true,
            'UTF-8',
            false);

  } // __construct

  /**
   * The footer chunk may contain two placeholders, %s1 is for current page,
   * %s2 for the count of pages. If you don't want a footer, send an '-'. If the
   * chunk is empty, the default 'Page $s1 / $s2' is used.
   *
   * @global object $modx
   * @param string $chunk The name of the chunk with the footer content.
   */
  private function setFooterContent($chunk)
  {
    global $modx;

    if ($chunk == '-') {
      // The footer ist completly empty
      $this->_footerContent = '';
    } else {
      if (!empty($chunk)) {
        // Get the chunk
        $this->_footerContent = $modx->getChunk($chunk);
      }
    }
  } // setFooterContent

  /**
   * Sets the footer position from bottom.
   * 
   * @param int $value The footer position from bottom should be a minus number.
   * @throws If the given value is not a number.
   * @link http://www.tcpdf.org/examples/example_003.phps
   */
  private function setFooterPositionFromBottom($value)
  {
    if(is_numeric($value)) {
      $this->_footerPositionFromBottom = $value;
    } else {
      throw new Exception('The footer position from bottom is not numeric.');
    }
  } // setFooterPositionFromBottom

  /**
   * Sets the height for header images in mm.
   *
   * @param int $value The height for header images in mm.
   * @throws If the given value is not a number.
   */
  private function setHeaderImageHeight($value)
  {
    if(is_numeric($value)) {
      $this->_headerImageHeight = $value;
    } else {
      throw new Exception('The footer position from bottom is not numeric.');
    }
  } // setHeaderImageHeight

  /**
   * Replaces placeholders in the given text and returns content.
   *
   * @global object $modx
   * @param string $content The content where the placeholders will be replaced.
   * @param string $dateFormat The format string for date formats, default is
   *               Y-m-d.
   * @return string The content with the replaced placeholders.
   */
  private function replacePlaceholder($content, $dateFormat='Y-m-d')
  {
    global $modx;

    $result = $content;

    if (!empty($content)) {

      if ($this->getYamsId() == '') {
        // Replace [+pagetitle+]
        $result = str_replace(
                '[+pagetitle+]',
                $modx->documentObject['pagetitle'],
                $result
                );

        // Replace [+longtitle+]
        $result = str_replace(
                '[+longtitle+]',
                $modx->documentObject['longtitle'],
                $result
                );
      } else {
        // Replace [+pagetitle+]
        $result = str_replace(
                '[+pagetitle_(yams_id)+]',
                $modx->documentObject['pagetitle_'.$this->getYamsId()],
                $result
                );

        // Replace [+longtitle+]
        $result = str_replace(
                '[+longtitle_(yams_id)+]',
                $modx->documentObject['longtitle_'.$this->getYamsId()],
                $result
                );
      }

      // Replace [+website+]
      $result = str_replace(
              '[+website+]',
              $modx->getConfig('site_url'),
              $result
              );

      // Replace [+currentsite+]
      $result = str_replace(
              '[+currentsite+]',
              $modx->makeUrl((int)$modx->documentObject['id'], '', '', 'full'),
              $result
              );

      // Replace [+author+]
      $user = $modx->getUserInfo($modx->documentObject['editedby']);
      $result = str_replace(
              '[+author+]',
              $user[fullname],
              $result
              );

      // Replace [+date+]
      $result = str_replace(
              '[+date+]',
              date($dateFormat, $modx->documentObject['publishedon']),
              $result
              );

      // Replace [+publishedon+]
      $result = str_replace(
              '[+publishedon+]',
              date($dateFormat, $modx->documentObject['publishedon']),
              $result
              );

      // Replace [+editedon+]
      $result = str_replace(
              '[+editedon+]',
              date($dateFormat, $modx->documentObject['editedon']),
              $result
              );

      // Replace [+createdon+]
      $result = str_replace(
              '[+createdon+]',
              date($dateFormat, $modx->documentObject['createdon']),
              $result
              );
    }

    return $result;
  } // replacePlaceholder

  /**
   * Sets the image file for the header.
   *
   * @param string $realPathToTCPDF The real path to the TCPDF libary.
   * @param string $value The image file name with its full path, allowed file
   *               extensions are jpg, gif, or png.
   * @throws If the file does not exists.
   * @throws If the image file has no extension.
   * @throws If the file extesnions is not one of e the allowed file extensions
   *         are jpg, gif, or png.
   */
  public function setImageFile($realPathToTCPDF, $value)
  {
    $checkFile = $realPathToTCPDF . self::TCPDF_IMAGE_FOLDER . $value;

    if (file_exists($checkFile)) {
      $tmp = explode('.', $value);

      if (count($tmp) > 1) {
        $fileType = strtoupper($tmp[count($tmp)-1]);

        if(in_array($fileType, array('JPG', 'GIF', 'PNG'))) {
          // MODX_BASE_PATH
          $this->_imageFile = $value;
          $this->_imageFileType = $fileType;
        } else {
          throw new Exception(sprintf('The image file extension "%s" is not jpg, gif, or png', $fileType));
        }
      } else {
        throw new Exception('The image file name has no extension.');
      }
    } else {
      throw new Exception(sprintf('The given image file "%s" name does not exist.', $checkFile));
    }
  } // setImageFile

  /**
   * Returns the image file including the path to the image.
   *
   * @return string The path to the image file
   */
  public function getImageFile()
  {
    return $this->_imageFile;
  } // getImageFile

  /**
   * Sets the header text of the PDF.
   *
   * @global object $modx
   * @param string $chunk The name of the chunk, that contains the header
   * @return string The content with the replaced placeholders.
   */
  public function setHeaderText($chunk)
  {
    global $modx;

    $this->_headerText = '';

    if (!empty($chunk)) {
      $this->_headerText = $this->replacePlaceholder(
              $modx->getChunk($chunk),
              $this->_dateFormat);
    }

    return $this->_headerText;
  } // setHeaderText

  /**
   * Returns the header text.
   * 
   * @return string The header text.
   */
  public function getHeaderText()
  {
    return $this->_headerText;
  } // getHeaderText

  /**
   * Sets the header font type.
   *
   * @param string $value The name of the font for the header.
   * @throws If the given value is empty.
   */
  public function setHeaderFontType($value)
  {
    if(!empty($value)) {
      $this->_headerFontType = $value;
    } else {
      throw new Exception('The font type can\'t be empty.');
    }
  } // setHeaderFontType

  /**
   * Returns the header font type.
   *
   * @return string The header font type
   */
  public function getHeaderFontType()
  {
    return $this->_headerFontType;
  } // getHeaderFontType

  /**
   * Sets the bold option for the header text.
   *
   * @param boolean $value If the header font is bold, or not.
   * @throws If the given value is not a boolean.
   */
  public function setHeaderFontBold($value)
  {
    if (is_bool($value)) {
      if ($value) {
        $this->_headerFontBold = 'B';
      } else {
        $this->_headerFontBold = '';
      }
    } else {
      throw new Exception('The value is not a boolean');
    }
  } // setHeaderFontBold

  /**
   * Returns the bold shortcut.
   *
   * @return string The bold shortcut if true, otherwise empty string.
   */
  public function getHeaderFontBold()
  {
    return $this->_headerFontBold;
  } // getHeaderFontBold

  /**
   * Sets the font size of the header text.
   *
   * @param float $value The font size of the header text.
   * @throws If the given value is not a number.
   */
  public function setHeaderFontSize($value)
  {
    if(is_numeric($value)) {
      $this->_headerFontSize = $value;
    } else {
      throw new Exception('The font size is not numeric.');
    }
  } // setHeaderFontSize

  /**
   *  Returns the font size of the header.
   *
   * @return int The font size of the header
   */
  public function getHeaderFontSize()
  {
    return $this->_headerFontSize;
  } // getHeaderFontSize

  /**
   * Sets the footer font type.
   *
   * @param string $value The name of the font for the footer.
   * @throws If the given value is empty.
   */
  public function setFooterFontType($value)
  {
    if (!empty($value)) {
      $this->_footerFontType = $this->_footerFontType;
    } else {
      throw new Exception('The font type can\'t be empty.');
    }
  } // setFooterFontType

  /**
   * Returns the footer font type.
   * 
   * @return string The footer font type.
   */
  public function getFooterFontType()
  {
    return $this->_footerFontType;
  } // getFooterFontType

  /**
   * Sets the italic option for the footer text.
   *
   * @param boolean $value If the footer font is italic, or not.
   * @throws If the given value is not a boolean.
   */
  public function setFooterFontItalic($value)
  {
    if (is_bool($value)) {
      if ($value) {
        $this->_footerFontItalic = 'I';
      } else {
        $this->_footerFontItalic = '';
      }
    } else {
      throw new Exception('The value is not a boolean');
    }
  } // setFooterFontBold

  /**
   * Returns the footer font italic.
   *
   * @return string Empty, if false, otherwise I for italic.
   */
  public function getFooterFontItalic()
  {
    return $this->_footerFontItalic;
  } // getFooterFontItalic

  /**
   * Sets the font size of the footer text.
   *
   * @param float $value The font size of the footer text.
   * @throws If the given value is not numeric
   */
  public function setFooterFontSize($value)
  {
    if(is_numeric($value)) {
      $this->_footerFontSize = $value;
    } else {
      throw new Exception('The font size is not numeric.');
    }
  } // setFooterFontSize

  /**
   * Returns the footer font size.
   *
   * @return int The footer font size.
   */
  public function getFooterFontSize()
  {
    return $this->_footerFontSize;
  } // getFooterFontSize

  /**
   * Sets the page text.
   *
   * @param string $value The footer caption text (translation) for page.
   */
  public function setFooterPageCaption($value)
  {
    $this->_footerPageCaption = $value;
  } // setFooterPageCaption

  /**
   * Sets the page number separator.
   *
   * @param string $value Sets the separator between current page and of pages.
   */
  public function setFooterPageSeparator($value)
  {
    $this->_footerPageSeparator = $value;
  } // setFooterPageSeparator

  /**
   * Sets chunk defined content under the document content. The placeholders
   * in the chunk are replaced with the appropriate content.
   *
   * @global object $modx
   * @param <type> $chunk The name of the content chunk.
   * @return string The content footer HTML
   */
  public function setContentFooter($chunk)
  {
    global $modx;

    $this->_contentFooter = '';
    
    if (!empty($chunk)) {
      $this->_contentFooter = $this->replacePlaceholder(
              $modx->getChunk($chunk),
              $this->_dateFormat);
    }

    return $this->_contentFooter;
  } // setContentFooter

  /**
   * Returns the content footer with the replaced placeholsers.
   *
   * @return string The content footer HTML
   */
  public function getContentFooter()
  {
    return $this->_contentFooter;
  } // getContentFooter

  /**
   * Sets the YAMS id.
   *
   * @param <type> $id The YAMS id
   * @return string The YAMS id
   */
  public function setYamsId($id)
  {
    $this->_yamsId = $id;
    
    return $this->_yamsId;
  } // setYamsId

  /**
   * Returns the YAMS id.
   *
   * @return string The YAMS id
   */
  public function getYamsId()
  {
    return $this->_yamsId;
  } // getYamsId

  /**
   * Override SetKeywords, should not be available outside this class.
   * Reads the keyword from a template variable.
   * 
   * @global object $modx
   * @param $tvName $value The name of the template variable.
   */
  public function SetKeywords($tvName)
  {
    global $modx;
    $modxHelper = modxHelper::getInstance();

    if (!empty($tvName)) {
      $this->_keyWords = $modxHelper->getTVContent(
              $tvName,
              $modx->documentObject['id']);
    } else {
      $this->_keyWords = '';
    }
    parent::SetKeywords($this->_keyWords);
  } // setKeywords

  public function getKeywords()
  {
    return $this->_keyWords;
  } // getKeywords

  /**
   * Overrides the orignial SetHeaderData. This is the way for using an easy
   * site header.
   *
   * @global object $modx
   * @param string $realPathToTCPDF The real path to the TCPDF libary.
   */
  public function SetHeaderData($realPathToTCPDF)
  {
    global $modx;

    list($width, $height, $type, $attr) = getimagesize(
            $realPathToTCPDF . self::TCPDF_IMAGE_FOLDER . $this->getImageFile());

    parent::SetHeaderData(
            $this->getImageFile(),
            $this->_headerImageHeight,
            $modx->documentObject['pagetitle'],
            $this->getHeaderText()
            );
  } // SetHeaderData

  /**
   * The style is checked for beginning and end tags (<style> and </style>). If
   * the beginning or the end tag are not set, they are set here.
   *
   * @global object $modx
   * @param string $chunk The chunk containing the CSS style.
   * @return string The CSS style for the PDF document.
   */
  public function setCSS($chunk)
  {
    global $modx;

    $this->_cssStyle = $modx->getChunk($chunk);

    // Check whether the style start exists
    if (!strpos($this->_cssStyle, '<style>')) {
      $this->_cssStyle = '<style>' . $this->_cssStyle;
    }

    // Check whether the style end exists
    if (!strpos($this->_cssStyle, '</style>')) {
      $this->_cssStyle = $this->_cssStyle . '</style>';
    }

    // Add a line break
    $this->_cssStyle .= "\n";

    return $this->_cssStyle;
  } // setCSS

  /**
   * Returns the CSS styles for the PDF document.
   *
   * @return string The CSS style for the PDF document.
   */
  public function getCSS()
  {
    return $this->_cssStyle;
  } // getCSS

  /**
   * Returns whether to use CSS style, or not.
   *
   * @return boolean Wether to use CSS style, or not.
   */
  public function useCSS()
  {
    return !empty($this->_cssStyle);
  } // useCSS

  /**
   * Sets the date format for all operations, handling with dates.
   *
   * @param string $value The format string for date formats, for example Y-m-d.
   * @throws If $value is empty, because empty format strings are not accepted.
   */
  public function setDateFormat($value)
  {
    if (!empty($value)) {
      $this->_dateFormat = $value;
    } else {
      throw new Exception('The value for the date format can\'t be empty.');
    }
  } // setDate

  /**
   * Returns the date format string. The default value is Y-m-d.
   *
   * @return string The format string for date formats, for example Y-m-d.
   */
  public function getDateFormat()
  {
    return $this->_dateFormat;
  } // getDateFormat

  /**
   * Sets whether the inline CSS should be stripped from the  content, or not.
   *
   * @param boolean $value Whether the inline CSS should be stripped from the
   *                content, or not.
   * @throws If $value is not boolean.
   */
  public function setStripCSSFromContent($value)
  {
    if (is_bool($value)) {
      $this->_stripCSSFromContent = $value;
    } else {
      throw new Exception('$value must be of type boolean.');
    }
  } // setStripCSSFromContent

  /**
   * Returns whether the inline CSS should be stripped from the  content,
   * or not.
   *
   * @return boolean Whether the inline CSS should be stripped from the
   *                 content, or not. Default is true.
   */
  public function getStripCSSFromContent()
  {
    return $this->_stripCSSFromContent;
  } // getStripCSSFromContent

  /**
   * Sets Whether the long title should be in the PDF document above the
   * content, or not.
   *
   * @param boolean $value Whether the long title should be in the PDF document
   *                above the content, or not.
   * @throws if $value is not boolean.
   */
  public function setLongTitleAboveContent($value)
  {
    if (is_bool($value)) {
      $this->_longTitleAboveContent = $value;
    } else {
      throw new Exception('$value must be of type boolean.');
    }
  } // setLongTitleAboveContent

  /**
   * Returns whether the long title should be in the PDF document above the
   * content, or not.
   *
   * @return boolean Whether the long title should be in the PDF document
   *                above the content, or not. Default is true.
   */
  public function getLongTitleAboveContent()
  {
    return $this->_longTitleAboveContent;
  } // getLongTitleAboveContent

  /**
   * Whether to overwrite existing PDF documents, or not. Set rewrite to false,
   * to use it as a chaching mechanism. In this case you have to manually
   * delete existing PDF files, if the document content changes.
   *
   * @param boolean $value Whether to overwrite an existing PDF, or not.
   */
  public function setRewritePDF($value)
  {
    if (is_bool($value)) {
      $this->_rewritePDF = $value;
    } else {
      throw new Exception('$value must be of type boolean.');
    }
  } // setRewritePDF

  /**
   * Whether to overwrite existing PDF documents, or not.
   *
   * @return boolean Whether to overwrite an existing PDF, or not. Default is
   *                 true.
   */
  public function getRewritePDF()
  {
    return $this->_rewritePDF;
  } // getRewritePDF

  /**
   * When $sourceIsChunk is true, the variable $content should contain the name
   * of a chunk. The chunk should contain a template in HTML style with MODX
   * placeholders, snippets, and chunks. This works the same way, as a MODX
   * Document, because it makes use of the MODX function to parse a document.
   * Otherwise the content of the current document is used for the PDF content.
   * Links are translated into links with the complete URI, because they would
   * not work otherwise.
   *
   * @global object $modx
   * @param string $content Contains the document content or the a chunk, if
   *               $sourceIsChunk is true. Default is empty.
   * @param boolean $sourceIsChunk Whether the content is in a chunk and
   *                $content contains the chunk name, or not. Default is false.
   * @return string The content for the PDF document.
   */
  public function setContent($sourceIsChunk=false, $content='')
  {
    global $modx;
    $result = '';
    $modxHelper = modxHelper::getInstance();

    if ($sourceIsChunk) {
      // Create the content with a chunk

      $chunk = $modx->getChunk($content);

      if ($this->getYamsId() != '') {
        $chunk = str_replace('(yams_id)', $this->getYamsId(), $chunk);
      }

      // Parse the content from the chunk with the MODX functions
      $result = $modx->parseDocumentSource($chunk);

        // Strip inline CSS
      if($this->_stripCSSFromContent) {
        $result = $modxHelper->removeInlineCSS($result);
      }

      // The content footer is only available with standard parameters
      $this->_contentFooter = '';
    } else {
      // Create the content with standard content and parameters

      // Check, whether the long title should be above the content
      if ($this->_longTitleAboveContent) {
        if ($this->getYamsId() == '')
          $result = '<h1>' . $modx->documentObject['longtitle'] . "</h1>\n";
        else{
          $result = '<h1>' . $modx->documentObject['longtitle'.'_'.$this->getYamsId()] . "</h1>\n";
        }
      }

      // Get document content
      if ($this->getYamsId() == '') {
        $documentContent = $modx->documentObject['content'];
      } else {
        $documentContent = $modx->documentObject['content'.'_'.$this->getYamsId()];
        $documentContent = str_replace('(yams_id)', $this->getYamsId(), $documentContent);
      }
  
      // Check for calls to htmlToPDF in the content and remove the calls
      $start = strpos($documentContent, '[!htmlToPDF?');
      $end = strpos($documentContent, '!]', $start);

      $documentContent = str_replace(substr($documentContent, $start, $end - $start + 2), '', $documentContent);
      
      // Add the document content and parse the content for chunks, etc.
      $result .= $modx->parseDocumentSource($documentContent);

      // Strip inline CSS
      if($this->_stripCSSFromContent) {
        $result = $modxHelper->removeInlineCSS($result);
      }
    }

    $result = $modxHelper->removeYAMSTags($result);

    // Parse the URLs and replace them to work from a PDF document,
    // add CSS style above the content
    // and add the content footer beneath the content
    $result = $this->getCSS()
            . $modxHelper->rewriteUrls($result)
            . $this->_contentFooter;

    // Set the content to the class variable
    $this->_content = $result;

    return $this->_content;
  } // setContent

  /**
   * Returns the document content.
   *
   * @return string The document content.
   */
  public function getContent()
  {
    return $this->_content;
  } // getContent

  /**
   * The Footer is overwritten with a user styled footer.
   */
  public function Footer() {
    // Position at 15 mm from bottom
    $this->SetY($this->_footerPositionFromBottom);
    // Set font
    $this->SetFont(
            $this->_footerFontType,
            $this->_footerFontItalic,
            $this->_footerFontSize);
    // Page number
    $this->Cell(
            0,
            10,
            sprintf(
              $this->_footerContent,
              $this->getAliasNumPage(),
              $this->getAliasNbPages()),
            0,
            false,
            'C',
            0,
            '',
            0,
            false,
            'T',
            'M');
  } // Footer

  /**
   * Overwrites the original writeHTML method to implement the calls to add a
   * new page, add the content, close with lastPage. Writes the PDF document to
   * the output folder and relocates the header to the PDF docoument.
   *
   * @global object $modx
   * @param string $basePath The base path to the output path.
   * @param string $outputPath The path, where the PDF files should be generated
   *               to.
   * @param string The base URL where MODX is available
   * @throws If the path for output does not exist ($basePath . $outPath).
   */
  public function generatePDF($basePath, $outputPath, $baseURL)
  {
    global $modx;

    if (realpath($outputPath) === false) {
      throw new Exception("The output path \"$basePath$outputPath\" does not exist.");
    } else {

      // Add a page
      parent::AddPage();

      // Output the content
      parent::writeHTML($this->getContent(), true, false, true, false, '');

      // Reset pointer to the last page
      parent::lastPage();

      // Close and output PDF document
      if ($this->getYamsId() == '') {
        $documentName = $outputPath .$modx->documentObject['alias'] . '.pdf';
      } else {
        $documentName = $outputPath .$modx->documentObject['alias'] . '_' . $this->getYamsId() . '.pdf';
      }
      if (!file_exists($basePath . $documentName)) {
        parent::Output($basePath . $documentName, 'F');
      } elseif ($this->_rewritePDF) {
        if (unlink($basePath . $documentName)) {
          parent::Output($basePath . $documentName, 'F');
        } else {
          $modx->logEvent(0, 2, sprintf('The file %1$s could not be deleted, please check the rights on the PDF output folder.', $documentName), 'htmlToPDF snippet');
        }
      }

      // Relocate to the PDF document
      header('Location: ' . $baseURL . $documentName);
    }
  } // generaterPDF
} // htmlToPDF