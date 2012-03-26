MODX-htmlToPDF
==============

This package contains source for an HTML to PDF converter for MODX Evolution, which is available at
the [MODX Website](http://modx.com/evolution/download/)

Currently it is not planned to create a copy for MODX Revolution.

If you use Microsoft Windows in any version, please notice, that all files of
this project have UNIX based line breaks.

You find news about this project on my site at http://stefanie-stoelting.de/htmltopdf-news.html,
the news feed is available at http://stefanie-stoelting.de/htmltopdf-news-rss-feed.html.

###License###

The source is licensed under the GNU Lesser General Public License, version 2.1
as published at [http://www.gnu.org/licenses/lgpl-2.1.html](http://www.gnu.org/licenses/lgpl-2.1.html)

###Usage of TCPDF###

This MODX snippet uses TCPDF, available at [http://www.tcpdf.org/](http://www.tcpdf.org/), to create the
PDF Files from the content of a web document, published with MODX. TCPDF is
part of the package. TCPDF itself is licensed under LGPL V3, as described at
[http://www.tcpdf.org/license.php](http://www.tcpdf.org/license.php).
The current version of TCPDF used in this package is 5.9.145.

The main goal is to create highly configurable PDF documents. This is possible
with parameters that one can use in the snippet tag and through the usage of chunks.
All chunks make use of the placeholders as defined in other MODX snippets.

###Installation###

###Upload###
For the installation you need to upload the TCPDF in the directory:  

    assets/lib/tcpdf/  

The snippet classes should be located at:  

    assets/snippets/htmlToPDF/  

###Create the snippet htmlToPDF###
Create a new snippet with the source of snippet.hmtlToPDF.php and call the
snippet within your templates, probably at the top of the template.
For the snippet call you can add several chunks as templates. Examples are in
the assets/snippets/htmlToPDF/chunks/ directory.

###Create the plugin htmlToPDF###
Create a new plugin with the source of plugin.htmlToPDF.
On the page "System Events" choose these two options in the area "Documents":

 * OnBeforeDocFormDelete
 * OnDocFormSave

###Example Snippet Call###

Here is an example for a call to htmlToPDF:

     [!htmlToPDF?  
        &author=``Stefanie Janine Stoelting``  
        &tvKeywords=``documentTags``  
        &headerLogo=``logo.png``  
        &chunkContentFooter=``pdf-contentfooter``  
        &chunkStandardHeader=``pdf-header-text``  
        &chunkStyle=``pdf-style``  
     !]  

###How to call the snippet###

Place the snippet in the template of the document, where you want your readers 
to download the content as PDF.
The advantage is, that you only have one call for all your documents.

If you have documents where that you do not want to publish as PDF and you only 
want to use one template, than try it with a template variable (TV) for this and 
PHX for the call and put your call to htmlToPDF into a snippet:

     [+phx:if=`[*printPDF*]`:is=`1`:then=`{{snippetCAllhtmlToPDF}}`:else=``+]

Where printPDF ist a possible name for the TV and snippetCAllhtmlToPDF is the 
name of the snippet.

PHX is available at http://modx.com/extras/package/phx

###Link Generation###
There is a chunk named pdf-link.txt that includes an example how to create a 
link to the current document. The example looks like the following:

    <a href="[*id*]?isPDF=true"Download">Download as PDF</a>


###Default Properties###

The snippet default properties are only needed, if you want to set TCPDF,
htmlToPDF, or the document output to other paths, as defined by default.
If you need to change this information, go the "Properties" tab on the
htmlToPDF snippet and add the following parameters to the field
"Default Properties" and afterwards insert your paths:
&basePath=The base path for TCPDF and htmlToPDF;string; &htmlToPdfPath=The path to the classes of htmlToPDF;string; &tcpdfPath=The path to TCPDF;string; &outputPdfPath=The path, where the PDF documents are stored. You need to give read, delete, and create rights to that folder (777).;string;

###Parameters###
The following parameters are available:
<table border="1" cellpadding="6">
<tr><th>Name</th><th>Description</th><th>Possible Values</th><th>Default</th></tr>

<tr><td>isPDF</td><td>Is added to the document URI, to identify PDF calls.</td><td>true</td><td></td></tr>

<tr><td>languageCode</td><td>The PDF document language code.</td><td>EN, DE,...</td><td>EN</td></tr>

<tr><td>setDateFormat</td><td>The date format string for all dates.</td><td>Y-m-d, d.m.Y</td><td>Y-m-d</td></tr>

<tr><td>marginLeft</td><td>The left margin of the document.</td><td>number in mm</td><td>10</td></tr>

<tr><td>marginRight</td><td>The right margin of the document.</td><td>number in mm</td><td>10</td></tr>

<tr><td>marginTop</td><td>The top margin of the document.</td><td>number in mm</td><td>30</td></tr>

<tr><td>marginBottom</td><td>The top margin of the document.</td><td>number in mm</td><td>25</td></tr>

<tr><td>marginHeader</td><td>The header margin of the document.</td><td>number in mm</td><td>5</td></tr>

<tr><td>marginFooter</td><td>The footer margin of the document.</td><td>number in mm</td><td>10</td></tr>

<tr><td>headerFontType</td><td>The header font type for standard headers.</td><td>Font</td><td>helvetica</td></tr>

<tr><td>headerFontSize</td><td>The font size for standard headers.</td><td>number</td><td>16</td></tr>

<tr><td>headerFontBold</td><td>Whether the header font is bold, or not.</td><td>Number 0 or 1</td><td>1</td></tr>

<tr><td>headerLogo</td><td>The logo for standard headers, the logo has to be in the<br /> folder assets/lib/tcpdf/images/ to be found byTCPDF.</td><td>GIF, JPG, PNG</td><td></td></tr>

<tr><td>footerPositionFromBottom</td><td>The footer position from the bottom.</td><td>number in mm</td><td>15</td></tr>

<tr><td>footerFontType</td><td>The footer font type for standard footers.</td><td>Font</td><td>helvetica</td></tr>

<tr><td>footerFontItalic</td><td>Whether the header font is italic, or not<br /> for standard footers.</td><td>Number 0 or 1</td><td>1</td></tr>

<tr><td>footerFontSize</td><td>The font size for standard footers.</td><td>number</td><td>8</td></tr>

<tr><td>contentFontType</td><td>The content font type for standard content.</td><td>Font</td><td>times</td></tr>

<tr><td>contentFontSize</td><td>The font size for standard content.</td><td>number</td><td>10</td></tr>

<tr><td>longTitleAboveContent</td><td>Whether the documents long title should be in the <br />document above the content, or not, only<br /> for standard content.</td><td>Number 0 or 1</td><td>1</td></tr>

<tr><td>stripCSSFromContent</td><td>Strip in-line CSS, or not, only for standard content.</td><td>Number 0 or 1</td><td>1</td></tr>

<tr><td>rewritePDF</td><td>If a PDF document exists, the document is not rewritten <br />every time, when the PDF document is requested. This <br />is a cache function for PDF documents.</td><td>Number 0 or 1</td><td>1</td></tr>

<tr><td>author</td><td>Author for PDF document properties.</td><td>string</td><td></td></tr>

<tr><td>tvKeywords</td><td>A template variable for keywords for the PDF document properties. <br />Keywords are comma separated, you may reuse <br />keywords for tag clouds.</td><td>Template Variable</td><td></td></tr>

<tr><td>chunkHeader</td><td>Chunk for customized headers</td><td>Chunk</td><td></td></tr>

<tr><td>chunkContentFooter</td><td>Chunk for customized text placed under the content. For example <br /> for a link to the current document on the website inside the PDF <br /> document. Only used with standard content.</td><td>Chunk</td><td></td></tr>

<tr><td>chunkStyle</td><td>A chunk for CSS styles in the PDF document.</td><td>Chunk</td><td></td></tr>

<tr><td>chunkContent</td><td>A chunk for individual arrangement of the <br />content in the document.</td><td>Chunk</td><td></td></tr>

<tr><td>fontMonoSpaced</td><td>The Monospaced font.</td><td>Font</td><td>courier</td></tr>

<tr><td>imageScaleRatio</td><td>The scale ratio for images.</td><td>number</td><td>1.25</td></tr>

<tr><td>footerChunk</td><td>The footer chunk name.</td><td>Chunk</td><td>Page %1s / %2s</td></tr>

<tr><td>basePath</td><td>The base path for TCPDF and htmlToPDF.</td><td>string</td><td>MODX_BASE_PATH</td></tr>

<tr><td>htmlToPdfPath</td><td>The path to the classes of htmlToPDF.</td><td>string</td><td>assets/snippets/htmlToPDF/|</td></tr>

<tr><td>tcpdfPath</td><td>The path to TCPDF.</td><td>string</td><td>assets/lib/tcpdf/</td></tr>

<tr><td>outputPdfPath</td><td>The path, where the PDF documents are stored. You <br />need to give read, delete, and create rights to that folder (777).</td><td>string</td><td>assets/pdf/</td></tr>

<tr><td>headerImageHeight</td><td>Sets the height for a logo in the header of the PDF document in mm.</td><td>int</td><td>20</td></tr>

<tr><td>printHeader</td><td>Whether to print a header, or not.</td><td>Number 0 or 1</td><td>1</td></tr>

<tr><td>printFooter</td><td>Whether to print a footer, or not.</td><td>Number 0 or 1</td><td>1</td></tr>

<tr><td>lineColor</td><td>String with 3 comma separated values as RGB.</td><td>string</td><td>0,0,0</td></tr>

</table>