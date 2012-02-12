The package contains source for the CMS MODX Evolution, available at
http://modxcms.com/

The current version of modx-htmlToPDF is 1.3.1, the release date is 2012/02/12.

If you use Microsoft Windows in any version, please notice, that all files of
this project have UNIX based line breaks.

The source is licensed under the GNU Lesser General Public License, version 2.1
as published on http://www.gnu.org/licenses/lgpl-2.1.html, the license is also
available in the license.txt file within this package.

This MODX snippet uses TCPDF, available at http://www.tcpdf.org/, to create the
PDF Files from the content of a web document, published with MODX. TCPDF is
part of the package. TCPDF itself is licensed under LGPL V3, as described at
http://www.tcpdf.org/license.php.
The current version of TCPDF used in this package is 5.9.145.

The main goal is to create highly configurable PDF documents. This is possible
with a lot of parameters, that one can use on a call and the usage of chunks.
All chunks make use of the placeholders as defined in other MODX snippets.

For the installation you need to upload the TCPDF in the directoy:
assets/lib/tcpdf/
The snippet classes should be located at:
assets/snippets/htmlToPDF/

Create the snippet htmlToPDF
Create a new snippet with the source of snippet.hmtlToPDF.php and call the
snippet within your templates, probably at the top of the template.
For the snippet call you can add several chunks as templates. Examples are in
the assets/snippets/htmlToPDF/chunks/ directory.

Create the plugin htmlToPDF
Create a new plugin with the source of plugin.htmlToPDF.
On the page "System Events" choose these two options in the area "Documents":
- OnBeforeDocFormDelete
- OnDocFormSave

Here is an example for a call to htmlToPDF:
[!htmlToPDF? &author=`Stefanie Janine Stoelting` &tvKeywords=`documentTags` &headerLogo=`logo.png` &chunkContentFooter=`pdf-contentfooter` &chunkStandardHeader=`pdf-header-text` &chunkStyle=`pdf-style`!]


How to call the snippet

Place the snippet in the template of the document, where you want your
readers to download the content as PDF.
The advantage is, that you only have one call for all your documents.

If you have documents where that you do not want to publish as PDF and you only 
want to use one template, than try it with a template variable (TV) for this and 
PHX for the call and put your call to htmlToPDF into a snippet:

     [+phx:if=`[*printPDF*]`:is=`1`:then=`{{snippetCAllhtmlToPDF}}`:else=``+]

Where printPDF ist a possible name for the TV and snippetCAllhtmlToPDF is the 
name of the snippet.

PHX is available at http://modx.com/extras/package/phx

The snippet default properties are only needed, if you want to set TCPDF,
htmlToPDF, or the document output to other paths, as defined by default.
If you need to change this information, go the "Properties" tab on the
htmlToPDF snippet and add the following parameters to the field
"Default Properties" and afterwards insert your paths:
&basePath=The base path for TCPDF and htmlToPDF;string; &htmlToPdfPath=The path to the classes of htmlToPDF;string; &tcpdfPath=The path to TCPDF;string; &outputPdfPath=The path, where the PDF documents are stored. You need to give read, delete, and create rights to that folder (777).;string;

The following paramaters are available:

---------------------------------------------------------------------------------------------------------------------
| Name                       | Description                                    | Possible Values   | Default         |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| isPDF                      | Is added to the document URI, to identify PDF  | true              |                 |
|                            | calls.                                         |                   |                 |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| languageCode               | The PDF document language code.                | EN, DE,...        | EN              |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| setDateFormat              | The date format string for all dates.          | Y-m-d, d.m.Y      | Y-m-d           |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| marginLeft                 | The left margin of the document.               | number in mm      | 10              |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| marginRight                | The right margin of the document.              | number in mm      | 10              |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| marginTop                  | The top margin of the document.                | number in mm      | 30              |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| marginBottom               | The top margin of the document.                | number in mm      | 25              |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| marginHeader               | The header margin of the document.             | number in mm      | 5               |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| marginFooter               | The footer margin of the document.             | number in mm      | 10              |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| headerFontType             | The header font type for standard headers.     | Font              | helvetica       |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| headerFontSize             | The font size for standard headers.            | number            | 16              |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| headerFontBold             | Whether the header font is bold, or not.       | Number 0 or 1     | 1               |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| headerLogo                 | The logo for standard headers, the logo has to | GIF, JPG, PNG     |                 |
|                            | be in the folder assets/lib/tcpdf/images/ to   |                   |                 |
|                            | be found byTCPDF.                              |                   |                 |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| footerPositionFromBottom   | The footer position from the bottom.           | number in mm      | 15              |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| footerFontType             | The footer font type for standard footers.     | Font              | helvetica       |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| footerFontItalic           | Whether the header font is italic, or not for  | Number 0 or 1     | 1               |
|                            | standard footers.                              |                   |                 |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| footerFontSize             | The font size for standard footers.            | number            | 8               |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| contentFontType            | The content font type for standard content.    | Font              | times           |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| contentFontSize            | The font size for standard content.            | number            | 10              |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| longTitleAboveContent      | Whether the documents long title should be in  | Number 0 or 1     | 1               |
|                            | the document above the content, or not, only   |                   |                 |
|                            | forstandard content.                           |                   |                 |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| stripCSSFromContent        | Strip in-line CSS, or not, only for standard   | Number 0 or 1     | 1               |
|                            | content.                                       |                   |                 |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| rewritePDF                 | If a PDF document exists, the document is not  | Number 0 or 1     | 1               |
|                            | rewritten every time, when the PDF document is |                   |                 |
|                            | requested. This is a cache function for PDF    |                   |                 |
|                            | documents.                                     |                   |                 |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| author                     | Author for PDF document properties.            | string            |                 |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| tvKeywords                 | A template variable for keywords for the PDF   | Template Variable |                 |
|                            | document properties. Keywords are comma        |                   |                 |
|                            | separated, you may reuse keywords for tag      |                   |                 |
|                            | clouds.                                        |                   |                 |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| chunkHeader                | Chunk for customized headers                   | Chunk             |                 |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| chunkContentFooter         | Chunk for customized text placed under the     | Chunk             |                 |
|                            | content. For example for a link to the current |                   |                 |
|                            | document on the website inside the PDF         |                   |                 |
|                            | document. Only used with standard content.     |                   |                 |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| chunkStyle                 | A chunk for CSS styles in the PDF document.    | Chunk             |                 |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| chunkContent               | A chunk for individual arrangement of the      | Chunk             |                 |
|                            | content in the document.                       |                   |                 |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| fontMonoSpaced             | The Monospaced font.                           | Font              | courier         |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| imageScaleRatio            | The scale ratio for images.                    | number            | 1.25            |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| footerChunk                | The footer chunk name.                         | Chunk             | Page %1s / %2s  |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| basePath                   | The base path for TCPDF and htmlToPDF.         | string            | MODX_BASE_PATH  |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| htmlToPdfPath              | The path to the classes of htmlToPDF.          | string            | assets/snippets/|
|                            |                                                |                   | htmlToPDF/      |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| tcpdfPath                  | The path to TCPDF.                             | string            | assets/lib/     |
|                            |                                                |                   | tcpdf/          |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| outputPdfPath              | The path, where the PDF documents are stored.  | string            | assets/pdf/     |
|                            | You need to give read, delete, and create      |                   |                 |
|                            | rights to that folder (777).                   |                   |                 |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| headerImageHeight          | Sets the height for a logo in the header of    | int               | 20              |
|                            | the PDF document in mm.                        |                   |                 |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| printHeader                | Whether to print a header, or not.             | Number 0 or 1     | 1               |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| printFooter                | Whether to print a footer, or not.             | Number 0 or 1     | 1               |
|----------------------------|------------------------------------------------|-------------------|-----------------|
| lineColor                  | String with 3 comma separated values as RGB.   | string            | 0,0,0           |
---------------------------------------------------------------------------------------------------------------------
