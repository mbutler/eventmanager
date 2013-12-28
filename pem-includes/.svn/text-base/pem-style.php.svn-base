<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: style.php

Manages the link include of style information and provides charset meta information.  
============================================================================= */

global $refresh_rate;

echo '<meta http-equiv="Content-Type" content="text/html; charset=' . $content_charset . '" />' . "\n";
if ($refresh_rate != 0) 
{
   echo '<meta http-equiv="Refresh" content="'.$refresh_rate.'" />' . "\n";
}
// pem_cache_set("pem_template", "red");

$pem_template_path = $PEM_PATH . "pem-themes/" . $pem_theme;
echo '<link rel="stylesheet" type="text/css" href="' . $pem_template_path . '/css.php" />' . "\n";
echo '<!--[if IE]>' . "\n";
echo '<link rel="stylesheet" type="text/css" href="' . $pem_template_path . '/iehacks.css" />' . "\n";
echo '<![endif]-->' . "\n";

?>
