<?php
require_once "session.php";
require_once "db.php";

function print_head($css_files, $title, $includesDatatable = FALSE) {
    $css = "";
    foreach ($css_files as $file) {
        $css = $css."\n"."<link rel=\"stylesheet\" href=\"".$file."\">";
    }

    $db = getDB();
    $logo = getOption($db, "logo") ?: "https://www.iaeste.de/files/2019/04/iaeste-logo.png";

    $datatableResources = "";
    if($includesDatatable) {
        $datatableResources = <<<DT
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.23/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.23/datatables.min.js"></script>
        DT;
    }

    echo <<<EOF
    <html>
        <head>
            $css
            $datatableResources
            <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <title>$title</title>
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
