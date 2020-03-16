<?php
require_once "session.php";

function print_head($css_files) {
    $css = "";
    foreach ($css_files as $file) {
        $css = $css."\n"."<link rel=\"stylesheet\" href=\"".$file."\">";
    }

    echo <<<EOF
    <html>
        <head>
            $css
            <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <title>IAESTE CRM</title>
        </head>
EOF;
}

function print_tail() {
    echo<<<EOF
    </html>
EOF;
}

?>
