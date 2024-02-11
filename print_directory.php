<?php
function printDirectory($dir) {
    $files = scandir($dir);
    echo "<ul>";
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            $path = $dir . '/' . $file;
            echo "<li>$file";
            if (is_dir($path)) {
                printDirectory($path);
            }
            echo "</li>";
        }
    }
    echo "</ul>";
}

// Specify the directory path you want to print
$directoryPath = __DIR__; // Prints the current directory

// Call the function to print the directory structure
printDirectory($directoryPath);
?>
