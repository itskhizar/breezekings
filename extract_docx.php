<?php
$docxPath = 'include/sqls/Filenod_Academy_Grading_Timetable_Well_Designed.docx';

$zip = new ZipArchive();
if ($zip->open($docxPath) === TRUE) {
    $xml = simplexml_load_string($zip->getFromName('word/document.xml'));
    $ns = $xml->getNamespaces(true);
    $xml->registerXPathNamespace('w', $ns['w']);
    $texts = $xml->xpath('//w:t');
    foreach($texts as $t) {
        echo (string)$t;
    }
    $zip->close();
} else {
    echo "Failed to open docx file.";
}
?>
