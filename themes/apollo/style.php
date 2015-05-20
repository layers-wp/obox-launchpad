<?php header('Content-type: text/css');
global $apollo_theme_options, $apollo_css_options;
if(isset($apollo_theme_options["background"]) && $apollo_theme_options["background"] != "")
echo "body{background: url(".$apollo_theme_options["background"].") top no-repeat fixed; -webkit-background-size: cover; -moz-background-size: cover; }";
if(isset($apollo_css_options["css"]) && $apollo_css_options["css"] != "")
echo "\n".$apollo_css_options["css"];