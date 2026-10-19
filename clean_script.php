<?php
// Fix Product/create.blade.php
$createFile = 'resources/views/dashboard/pages/Product/create.blade.php';
$content = file_get_contents($createFile);

$startMarker = "$('#offer_type, #price, #offer_value').on('change input', calculateFinalPrice);";
$endMarker = "    </script>";

$startPos = strpos($content, $startMarker);
$endPos = strpos($content, $endMarker, $startPos);

if ($startPos !== false && $endPos !== false) {
    $before = substr($content, 0, $startPos + strlen($startMarker));
    $after = substr($content, $endPos);
    $content = $before . "\r\n        });\r\n" . $after;
    file_put_contents($createFile, $content);
    echo "create.blade.php cleaned successfully\n";
} else {
    echo "Error finding markers in create.blade.php\n";
}

// Fix Product/edit.blade.php
$editFile = 'resources/views/dashboard/pages/Product/edit.blade.php';
$contentEdit = file_get_contents($editFile);

$startMarkerEdit = "$('#offer_value_div').show();";
$startPosEdit = strpos($contentEdit, $startMarkerEdit);
if ($startPosEdit !== false) {
    // Find the closing brace of the if block
    $closeBracePos = strpos($contentEdit, "}", $startPosEdit);
    if ($closeBracePos !== false) {
        $endPosEdit = strpos($contentEdit, $endMarker, $closeBracePos);
        if ($endPosEdit !== false) {
            $beforeEdit = substr($contentEdit, 0, $closeBracePos + 1);
            $afterEdit = substr($contentEdit, $endPosEdit);
            $contentEdit = $beforeEdit . "\r\n        });\r\n" . $afterEdit;
            file_put_contents($editFile, $contentEdit);
            echo "edit.blade.php cleaned successfully\n";
        }
    }
}
