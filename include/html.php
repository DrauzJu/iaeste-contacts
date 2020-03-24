<?php
require_once "session.php";
require_once "db.php";

function print_head($css_files) {
    $css = "";
    foreach ($css_files as $file) {
        $css = $css."\n"."<link rel=\"stylesheet\" href=\"".$file."\">";
    }

    $db = getDB();
    $logo = getOption($db, "logo") ?: "https://www.iaeste.de/files/2019/04/iaeste-logo.png";

    echo <<<EOF
    <html>
        <head>
            $css
            <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <title>IAESTE CRM</title>
        </head>

        <body>
            <img class="logo" src="$logo"/>
EOF;
}

function print_tail() {
    echo<<<EOF
        </body>
    </html>
EOF;
}

?>
