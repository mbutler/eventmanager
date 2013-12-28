<?php
header ("Content-type: text/css");

$primary_font_family = "Verdana, Helvetica, Arial, sans-serif";
$alternate_font_family = "Arial, Verdana, Helvetica, sans-serif";
$head_font_family = "Georgia, Times, serif";

$color_table['Red'] = array("AD0C0C","990000","770000","880000","DD0000","F2E4E4","FFF8F8");
$color_table['Pink'] = array("AD0C5D","99004C","78003C","870044","DE006F","F2E4EB","FFF7FB");
$color_table['Magenta'] = array("AD0CAD","990099","770077","880088","DD00DD","F2E4F2","FFF8FF");
$color_table['Violet'] = array("5D0CAD","4D0099","3C0078","440087","6F00DE","EBE4F2","FBF7FF");
$color_table['Blue'] = array("0C0CAD","000099","000077","000088","0000DD","E4E4F2","F8F8FF");
$color_table['Azure'] = array("0C5DAD","004D99","003C78","004487","006FDE","E4EBF2","F7FBFF");
$color_table['Cyan'] = array("0CADAD","009999","007777","008888","00DDDD","E4F2F2","F8FFFF");
$color_table['Green'] = array("0CAD0C","009900","007700","008800","00DD00","E4F2E4","F8FFF8");
$color_table['Olive'] = array("9A9A3D","909000","727200","808000","CCCC00","F2F2E4","FFFFF8");
$color_table['Yellow'] = array("CDCD00","999900","777700","888800","DDDD00","F2F2E4","FFFFF8");
$color_table['Gold'] = array("CBA300","997A00","786000","876C00","DEB100","F2EFE4","FFFDF7");
$color_table['Orange'] = array("BF6000","994C00","783C00","874400","DE6F00","F2EBE4","FFFBF7");
$color_table['Tan'] = array("B39162","998367","786750","87755B","DEBE95","F2ECE4","FFFCF7");
$color_table['Black'] = array("444444","4D4D4D","2B2B2B","3B3B3B","919191","F2F2F2","F8F8F8");

$theme_color = "Orange";

$error_color = "#" . $color_table[$theme_color][0];

$header_color = "#FFF";   // color of the overall header
$header_bg = "#" . $color_table[$theme_color][0];   // background color of the overall header
$header_nav_bg = "#" . $color_table[$theme_color][2];  // background color of the header nav strip
$header2_color = "#FFF";  // color of the secondary header
$header2_bg = "#888";     // background color of the secondary header

$primary_bg = "#FFF";     // overall background at body level
$fieldset_bg = "#F8F8F8"; // background used to surround form data
$legend_color = "#" . $color_table[$theme_color][3];   // fieldset title color

$header_color_1 = "#000"; // color <h1> tag
$header_color_2 = "#" . $color_table[$theme_color][1]; // color <h2> tag
$header_color_3 = "#" . $color_table[$theme_color][1]; // color <h3> tag
$header_color_4 = "#666"; // color <h4> tag

$link_color_1 = "#" . $color_table[$theme_color][0]; // default link color
$link_color_2 = "#" . $color_table[$theme_color][3]; // visited link color
$link_color_3 = "#" . $color_table[$theme_color][4];    // active link color
$link_color_4 = "#" . $color_table[$theme_color][4];    // hover link color

$border_color = "#" . $color_table[$theme_color][0];
$field_bg = "#" . $color_table[$theme_color][5];
$field_focus_bg = "#FFF";

$nav_on_color = "#FFF";
$nav_off_color = "#000";
$nav_button_on_color = "#FFF";
$nav_button_off_color = "#000";
$button_on_color = "#FFF";
$button_off_color = "#000";
$header_button_on_color = "#000";
$header_button_off_color = "#000";

$minical_header_color = "#FFF";   // background color of the overall header
$minical_header_bg = "#" . $color_table[$theme_color][0];   // background color of the overall header


// Specialty colors used by event categories
$unapproved_color = "#C0C";
$private_color = "#606";
$cancelled_color = "#930";
$action_color = "#000";

// CSS begins here
echo '

/* =============================================================================
============================= BROWSER RESET ====================================
============================================================================= */

html, body, div, span, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, code, del, dfn, em, img, q, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td {margin:0;padding:0;border:0;font-weight:inherit;font-style:inherit;font-size:100%;font-family:inherit;vertical-align:baseline;}
body {line-height:1.2;color:black;background:white;}
table {border-collapse:collapse;border-spacing:0;}
caption, th, td {text-align:left;font-weight:normal;}
table, td, th {vertical-align:middle;}
blockquote:before, blockquote:after, q:before, q:after {content:"";}
blockquote, q {quotes:"" "";}
a img {border:none;}
ol, ul {list-style:none;}


/* =============================================================================
============================= PEM TAG SETUP ====================================
============================================================================= */

body               { font-family:' . $primary_font_family . '; }
p                  { margin-bottom:1em; }
ul,ol              { padding-left:2em; }
br                 { clear:left; display:block; line-height:0; }
em                 { color:#800; }

a         { text-decoration:none; }
a:link    { color:' . $link_color_1 . '; }
a:visited { color:' . $link_color_2 . '; }
a:active  { color:' . $link_color_3 . '; }
a:hover   { color:' . $link_color_4 . '; text-decoration:underline; }

fieldset { font-size:80%; border:1px solid #DDD; background-color:' . $fieldset_bg . '; margin:10px 0; padding:15px 15px; clear:left; position:relative; }
legend { font-size:150%; background-color:' . $primary_bg . '; font-weight:normal; letter-spacing:-0.03em; line-height:1; color:' . $legend_color . '; padding:0 0.5em; }

fieldset.sub { font-size:100%; border:1px solid #DDD; background-color:' . $fieldset_bg . '; margin:10px 0; padding:10px 10px; clear:left; position:relative; }
fieldset.sub legend { font-size:100%; font-weight:bold; background-color:' . $fieldset_bg . '; letter-spacing:-0.03em; line-height:1; color:#000; padding:0 0.5em; }

.indent   { margin:2px 0 10px 30px; }
.indentsm { margin:2px 0 10px 15px; }
.error { list-style-type:none; margin:0 0 10px 10px; padding-left:0; font-size:100%; color:' . $error_color . '; }
.error li { margin:0.1em 0; padding-left:14px; background:transparent url(' . $pem_template_path . 'bullet.png) no-repeat 0 .2em; }

.bullets { list-style-type:none; margin:0 0 10px 10px; padding-left:0; font-size:100%; }
.bullets li  { margin:0.1em 0; padding-left:14px; background:transparent url(' . $pem_template_path . 'bullet.png) no-repeat 0 .2em; }
.bullets li.head { padding-left:0; background:none; font-weight:bold; }
.bullets li.reg { padding-bottom:5px; }

.numbers { list-style-type:decimal; margin:0 0 10px 30px; padding-left:0; font-size:100%; }
.numbers li  { margin:0.1em 0; padding-left:0; }


.hidden { display:none; visibility:hidden; }
.important { color:#900 }

/* =============================================================================
========================== HEADERS AND FOOTERS =================================
============================================================================= */

h1        { font:bold 150% ' . $head_font_family . '; color:' . $header_color_1 . '; }
h2        { font:bold 140% ' . $head_font_family . '; color:' . $header_color_2 . '; }
h3        { font:bold 130% ' . $alternate_font_family . '; color:' . $header_color_2 . '; text-transform:uppercase; margin-top:10px; }
h4        { font:bold 130% ' . $alternate_font_family . '; color:' . $header_color_3 . '; }
h5        { font:bold 110% ' . $alternate_font_family . '; color:' . $header_color_2 . '; text-transform:uppercase; margin-top:10px; }
h1.light  { font:normal 160% ' . $head_font_family . '; color:' . $header_color_1 . '; }
h2.light  { font:normal 150% ' . $head_font_family . '; color:' . $header_color_2 . '; }
h3.light  { font:normal 150% ' . $head_font_family . '; color:' . $header_color_2 . '; }
h4.light  { font:normal 140% ' . $alternate_font_family . '; color:' . $header_color_2 . '; }
h5.light  { font:normal 140% ' . $alternate_font_family . '; color:' . $header_color_2 . '; }
h1.date   { font-family:' . $primary_font_family . '; }

.h3        { font:bold 130% ' . $alternate_font_family . '; color:' . $header_color_2 . '; text-transform:uppercase; }
.sectionhead { font:bold 140% ' . $head_font_family . '; color:#000; background-color:#DDD; padding:0.1em 0.5em; margin:0 -0.5em 0.4em -0.5em; }

#header   { font-family:' . $head_font_family . '; color:' . $header_color . '; background-color:' . $header_bg . '; padding:2px 10px 4px 10px; position:relative; border-bottom:1px solid #444; }
#content  { font-weight:normal; padding:1em 1%; clear:both; position:relative; margin-top:10px; }
#footer   { font-size:80%; text-align:center; margin:4em auto; clear:both; }

#content-print  { font-weight:normal; padding:1em 1%; clear:both; position:relative; line-height:1.4em; }

#header-simple  { font-family:' . $head_font_family . '; color:' . $header_color . '; background-color:' . $header_bg . '; padding:0.2em 20%; border-bottom:1px solid #444; }
#content-simple { font-weight:normal; padding:2em 20%; }

#header-title              { font-weight:bold; font-size:180%; padding:0.3em 0.1em; }
#header-image              {  }
#header-login              { float:right; }
#header-login input[type=submit] { padding:.2em; }
#header-nav                { float:right; margin:-2px 10px 0 0; padding:0 0 0 4px; font-weight:bold; font-size:80%; font-family:' . $primary_font_family . '; background:url(' . $pem_template_path . 'headnavl.png) no-repeat left top; }
#header-nav span           { float:left; padding:1px 10px 0 5px; display:block; height:24px; background:url(' . $pem_template_path . 'headnav.png) no-repeat right top; }
#header-search             { float:right; }



#header-goto               { float:right; }
#header-goto-text          { float:left; text-transform:uppercase; font-weight:bold; font-size:80%; }
#header-goto-today         { float:left; }
#header-goto-today input[type=submit] { padding:.2em; }
#header-goto-month         { float:left; margin-top:2px; width:100px;  }

#header a                  { text-decoration:none; }
#header a:link             { color:' . $header_color . '; }
#header a:visited          { color:' . $header_color . '; }
#header a:active           { color:' . $header_color . '; }
#header a:hover            { color:' . $header_color . '; }

#header-simple a                  { text-decoration:none; }
#header-simple a:link             { color:' . $header_color . '; }
#header-simple a:visited          { color:' . $header_color . '; }
#header-simple a:active           { color:' . $header_color . '; }
#header-simple a:hover            { color:' . $header_color . '; }

#header select, option     { font-size:90%; font-weight:normal; font-family:' . $primary_font_family . '; background-color:#FFF; }
#header option             { border:1px solid #F8F8F8; }
#header input[type=submit] { font-size:60%; font-weight:normal; font-family:' . $primary_font_family . '; }

/* =============================================================================
========================== NAVIGATION AND BUTTONS ==============================
============================================================================= */

#navigation                { float:left; width:100%; font-size:90%; line-height:normal; background:' . $header2_bg . '; border-bottom:1px solid #000; }
#navigation h2             { color:' . $header2_color . '; padding:10px 5px 0 15px; float:left; }
#navigation ul             { float:right; padding:10px 5px 0 10px; list-style:none; position:relative; bottom:-1px; }
#navigation li             { display:inline; }
#navigation a              { float:left; background:url(' . $pem_template_path . 'navtabl.png) no-repeat left top; padding:0 0 0 4px; text-decoration:none; border-bottom:1px solid #000; }
#navigation a span         { float:left; display:block; background:url(' . $pem_template_path . 'navtab.png) no-repeat right top; padding:5px 13px 4px 6px; color:#000; }
#navigation a:hover        { background-position:0% -42px; text-decoration:none; }
#navigation a:hover span   { color:' . $header2_color . '; background-position:100% -42px; }
#navigation .on            { background-position:0% -84px; border-bottom:1px solid ' . $primary_bg . '; }
#navigation .on span       { background-position:100% -84px; }
#navigation .on:hover      { background-position:0% -84px; text-decoration:none; }
#navigation .on:hover span { color:#000; background-position:100% -84px; }
/* Commented Backslash Hack hides rule from IE5-Mac \*/
#navigation a span         { float:none; }
#navigation .on span       { float:none; }
/* End IE5-Mac hack */

#navigation div                { float:right; margin:10px 5px 0 0; }
#navigation div a              { float:left; background:url(' . $pem_template_path . 'buttonl-sm.png) no-repeat left top; padding:0 0 0 3px; text-decoration:none; border:none; }
#navigation div a span         { float:left; height:17px; display:block; background:url(' . $pem_template_path . 'button-sm.png) no-repeat right top; padding:3px 9px 0 5px; color:' . $nav_button_off_color . '; font-size:11px; }
#navigation div a:hover        { background-position:0% -40px; text-decoration:none; }
#navigation div a:hover span   { color:' . $nav_button_on_color . '; background-position:100% -40px; }

#navigation div#navdate     { float:left; margin:10px 5px 0 0; }
#navdate select, option     { font-size:90%; font-weight:normal; font-family:' . $primary_font_family . '; background-color:#FFF; }
#navdate select             { border:1px solid #000; }
#navdate option             { border:1px solid #F8F8F8; }
#navdate input[type=submit] { font-size:60%; font-weight:normal; font-family:' . $primary_font_family . '; }
#navdate a { float:right; background:url(' . $pem_template_path . 'buttonl-sm.png) no-repeat left top; padding:0 0 0 3px; text-decoration:none; }
#navdate a span { float:left; height:20px; display:block; background:url(' . $pem_template_path . 'button-sm.png) no-repeat right top; padding:0 9px 0 5px; color:' . $button_off_color . '; font-size:11px; }
#navdate a:hover { background-position:0% -40px; text-decoration:none; }
#navdate a:hover span { color:' . $button_on_color . '; background-position:100% -40px; }
#jumptoform { float:left; }

.submit  { font:bold 100% ' . $secondary_font_family . '; color:' . $button_on_color . '; padding:0 0 0.3em 0.5em; margin:0 0 3px 3px; height:30px; background:transparent url(/pem-images/submit.png) no-repeat; border:0; }
.submitr { float:right; padding-right:11px; height:30px; background:transparent url(' . $pem_template_path . 'submitr.png) no-repeat top right; }

.installsubmit { float:right; padding:0 0 0 10px; list-style:none; }
.installsubmit li { display:inline; }
.installsubmit a { float:left; background:url(' . $pem_template_path . 'buttonl.png) no-repeat left top; padding:0 0 0 4px; text-decoration:none; }
.installsubmit a span { float:left; height:23px; display:block; background:url(' . $pem_template_path . 'button.png) no-repeat right top; padding:4px 13px 0 6px; color:' . $button_off_color . '; font-size:14px; }
.installsubmit a:hover { background-position:0% -27px; text-decoration:none; }
.installsubmit a:hover span { color:' . $button_on_color . '; background-position:100% -27px; }

.formsubmit { float:left; padding:0 0 0 50px; list-style:none; }
.formsubmit li { display:inline; }
.formsubmit a { float:left; background:url(' . $pem_template_path . 'buttonl.png) no-repeat left top; padding:0 0 0 4px; text-decoration:none; }
.formsubmit a span { float:left; height:23px; display:block; background:url(' . $pem_template_path . 'button.png) no-repeat right top; padding:4px 13px 0 6px; color:' . $button_off_color . '; font-size:14px; }
.formsubmit a:hover { background-position:0% -27px; text-decoration:none; }
.formsubmit a:hover span { color:' . $button_on_color . '; background-position:100% -27px; }

.formupdate { float:left; padding:0 0 0 50px; list-style:none; }
.formupdate li { display:inline; }
.formupdate a { float:left; background:url(' . $pem_template_path . 'buttonl.png) no-repeat left top; padding:0 0 0 4px; text-decoration:none; }
.formupdate a span { float:left; height:23px; display:block; background:url(' . $pem_template_path . 'button.png) no-repeat right top; padding:4px 13px 0 6px; color:' . $button_off_color . '; font-size:14px; }
.formupdate a:hover { background-position:0% -27px; text-decoration:none; }
.formupdate a:hover span { color:' . $button_on_color . '; background-position:100% -27px; }

.rightupdate { float:right; padding:0 0 0 10px; margin:-10px 0 0 0; list-style:none; }
.rightupdate li { display:inline; }
.rightupdate a { float:left; background:url(' . $pem_template_path . 'buttonl.png) no-repeat left top; padding:0 0 0 4px; text-decoration:none; }
.rightupdate a span { float:left; height:23px; display:block; background:url(' . $pem_template_path . 'button.png) no-repeat right top; padding:4px 13px 0 6px; color:' . $button_off_color . '; font-size:14px; }
.rightupdate a:hover { background-position:0% -27px; text-decoration:none; }
.rightupdate a:hover span { color:' . $button_on_color . '; background-position:100% -27px; }

.controls { float:right; padding:5px 0 5px 10px; list-style:none; }
.controls li { display:inline; }
.controls a { float:left; background:url(' . $pem_template_path . 'buttonl-sm.png) no-repeat left top; padding:0 0 0 3px; text-decoration:none; }
.controls a span { float:left; height:18px; display:block; background:url(' . $pem_template_path . 'button-sm.png) no-repeat right top;padding:2px 9px 0 5px; color:' . $button_off_color . '; font-size:11px; }
.controls a:hover { background-position:0% -40px; text-decoration:none; }
.controls a:hover span { color:' . $button_on_color . '; background-position:100% -40px; }

.controlslg { padding:0; list-style:none; }
.controlslg li { display:inline; }
.controlslg a { float:left; background:url(' . $pem_template_path . 'buttonl.png) no-repeat left top; padding:0 0 0 4px; text-decoration:none; }
.controlslg a span { float:left; height:23px; display:block; background:url(' . $pem_template_path . 'button.png) no-repeat right top; padding:4px 13px 0 6px; color:' . $button_off_color . '; font-size:14px; }
.controlslg a:hover { background-position:0% -27px; text-decoration:none; }
.controlslg a:hover span { color:' . $button_on_color . '; background-position:100% -27px; }

.controlsleft { float:left; padding:5px 0 5px 10px; list-style:none; }
.controlsleft li { display:inline; }
.controlsleft a { float:left; background:url(' . $pem_template_path . 'buttonl-sm.png) no-repeat left top; padding:0 0 0 3px; text-decoration:none; }
.controlsleft a span { float:left; height:18px; display:block; background:url(' . $pem_template_path . 'button-sm.png) no-repeat right top; padding:2px 9px 0 5px; color:' . $button_off_color . '; font-size:11px; }
.controlsleft a:hover { background-position:0% -40px; text-decoration:none; }
.controlsleft a:hover span { color:' . $button_on_color . '; background-position:100% -40px; }

.headersubmit { float:right; margin:7px 0 0 0;  list-style:none; }
.headersubmit li { display:inline; }
.headersubmit a { float:left; background:url(' . $pem_template_path . 'buttonl-sm.png) no-repeat left top; padding:0 0 0 3px; text-decoration:none; }
.headersubmit a span { float:left; height:18px; display:block; background:url(' . $pem_template_path . 'button-sm.png) no-repeat right top; padding:2px 9px 0 5px; color:' . $header_button_off_color . '; font-size:11px; text-transform:uppercase; }
.headersubmit a:hover { background-position:0% -20px; text-decoration:none; }
.headersubmit a:hover span { color:' . $header_button_on_color . '; background-position:100% -20px; }
.headersubmit .nobutton { background:none; height:1em; float:none; }

.buttonlink { margin:0 auto; padding:0; list-style:none; }
.buttonlink li { display:inline; }
.buttonlink a { float:left; background:url(' . $pem_template_path . 'buttonl.png) no-repeat left top; padding:0 0 0 4px; text-decoration:none; }
.buttonlink a span { float:left; height:23px; display:block; background:url(' . $pem_template_path . 'button.png) no-repeat right top; padding:4px 13px 0 6px; color:' . $button_off_color . '; font-size:14px; }
.buttonlink a:hover { background-position:0% -27px; text-decoration:none; }
.buttonlink a:hover span { color:' . $button_on_color . '; background-position:100% -27px; }

#previous { float:left; }
#previous a { float:left; background:url(' . $pem_template_path . 'buttonl-sm.png) no-repeat left top; padding:0 0 0 3px; text-decoration:none; }
#previous a span { float:left; height:18px; display:block; background:url(' . $pem_template_path . 'button-sm.png) no-repeat right top; padding:2px 9px 0 5px; color:' . $button_off_color . '; font-size:11px; }
#previous a:hover { background-position:0% -40px; text-decoration:none; }
#previous a:hover span { color:' . $button_on_color . '; background-position:100% -40px; }

#next { float:right; }
#next a { float:left; background:url(' . $pem_template_path . 'buttonl-sm.png) no-repeat left top; padding:0 0 0 3px; text-decoration:none; }
#next a span { float:left; height:18px; display:block; background:url(' . $pem_template_path . 'button-sm.png) no-repeat right top; padding:2px 9px 0 5px; color:' . $button_off_color . '; font-size:11px; }
#next a:hover { background-position:0% -40px; text-decoration:none; }
#next a:hover span { color:' . $button_on_color . '; background-position:100% -40px; }

.fscontrols { z-index:100; position:absolute; top:-24px; right:20px; }

.positionhead { margin:20px 0 -20px 0 }

.inlinebutton       { float:left; background:url(' . $pem_template_path . 'buttonl-sm.png) no-repeat left top; padding:0 0 0 3px; margin-left:10px; text-decoration:none; }
.inlinebutton span  { float:left; height:18px; display:block; background:url(' . $pem_template_path . 'button-sm.png) no-repeat right top; padding:2px 9px 0 5px; color:' . $button_off_color . '; font-size:11px; }
.inlinebutton:hover { background-position:0% -40px; text-decoration:none; }
.inlinebutton:hover span { color:' . $button_on_color . '; background-position:100% -40px; }

#mainform { padding-top:10px; clear:both; }


/* =============================================================================
=========================== FORM INPUTS AND LABELS =============================
============================================================================= */

label, input, select, textarea { float:left; margin:0 3px 3px 3px; }
label, input { display:block; }
label { white-space:nowrap; font-weight:bold; }
.label { float:left; margin:0 3px 3px 3px; display:block; white-space:nowrap; font-weight:bold; }
label.secondary  { margin:0 3px 3px 10px; }

input[type=text], input[type=password], textarea { font:normal 100% ' . $primary_font_family . '; color: #333; padding:0.2em; background:' . $field_bg . ' url(' . $pem_template_path . 'formfield.png) repeat-x top left; border:1px solid ' . $border_color . '; }
input[type=file] { font:normal 100% ' . $primary_font_family . '; color:#333; background:' . $field_bg . ';  border:1px solid ' . $border_color . '; }

input[type=submit] { font-size:80%; font-weight:normal; font-family:' . $primary_font_family . '; }
input[type=checkbox] { float:none; display:inline; margin:3px 0 0 4px; }
input[type=hidden] { display:none; }
input:focus, textarea:focus {","background:' . $field_focus_bg . '; }
select, option { font-size:100%; font-weight:normal; font-family:' . $primary_font_family . '; background-color:' . $field_bg . '; }
select         { border:1px solid ' . $border_color . '; }
option         { border:1px solid ' . $field_bg . '; padding:0 2px; }
option:hover   { color:#FFF; background-color:' . $border_color . '; }

th       { line-height:1em; padding:25px 5px 10px 5px; font-weight:bold; }
td       { text-align:center; }
td.label { text-align:left; }

.row1    { background-color:#EEE; }
.row2    { background-color:transparent; }
.note    { font:italic normal 100% ' . $alternate_font_family . '; margin-left:3px; }
.note2   { font-style:italic; font-weight:normal; font-family:' . $alternate_font_family . '; margin-left:3px; }
.note3   { font-weight:normal; font-family:' . $alternate_font_family . '; margin-left:3px; }
.requirednote { color:#800; }

.fieldextra { margin-left:5px; float:left; }

.datalist td { padding:1px; }
.datalist .controls { padding:0; margin:0 5px; }
.datalist .controlbox { width:180px; }
.datalist .controlboxwide { width:270px; }

#directions { color:#800; }
#directions li { color:#000; }

#errorbox {
position:absolute; visibility:hidden; display:none; top:0; right:5px;
z-index:90; width:250px; height:auto; overflow:auto; padding:5px; border:8px solid #800; background-color:#FFEAEA; color:#000;

/*
  top:50%;
  left:50%;
  margin-left:-150px;
  margin-top:-50px;
#errorbox li {  color:#FFF;}
*/
}


#errorbox h2 { float:left; font-family:' . $alternate_font_family . ';}
#errorbox .hide { text-transform:uppercase; float:right; }
#errorbox br { clear:both; }



/* =============================================================================
============================= ADMIN FORM SPACING ===============================
============================================================================= */

// These styles are cosmetic additions tied to default Engligh labels.
// Users of other languages may need to adjust these styles to suit the
// respective lengths of alternate words.

.neweventform label { }
.neweventform label.timeoccurs { width:100px; }   /* Date Begins/Ends */
.neweventform label.timeneeds  { width:150px; }   /* Time Begins/Ends */
.neweventform label.desc       { width:170px; }   /* Main Descriptive Fields */
.neweventform label.sublabel   { width:auto; }

.regform label       { width:100px; }

.areasform label       { width:130px; }
.spacesform label      { width:150px; }
.supplyform label      { width:260px; }
.supplyprofform label  { width:100px; }
.metainputform label   { }
.metacheckform label   { }
.metacontactform label { width:100px; }
.metaselectform label  { }
.categoryform label    { width:150px; }
.setupform label       { width:130px; }
.setupform input[type=checkbox] { float:left; display:inline; margin:2px 5px 0 0; }
.generalform label     { width:140px; }
.datetimeform label    { width:150px; }
.datetimeform label.sublabel    { width:auto; }
.schedulingform label  { width:200px; }
.schedulingform label.sublabel  { width:auto; margin-right:20px; }
.schedulingform .profilefields  { width:100px; }
.schedulingform .timeboundary   { width:100px; }
.interfaceform label   { width:100px; }
.accountform label     { width:200px; }
.defaultsform label    { width:150px; }
.settingsform label    { width:200px; }
.settingsform label.secondary  { width:auto; padding-right:20px; }
.fieldsform label      { width:150px; text-align:left;  }
.fieldsform select     { float:none; }
.regsettingsform label    { width:300px; }
.adduserform label     { width:150px; }
.userform .status      { float:right; margin:-10px 10px 0 0; white-space:nowrap; }
.userform .globaladmin { float:right; margin:-10px 10px 0 0; white-space:nowrap; }
.userform .idfield     { float:left; margin:0 10px 0 0; }
.userform .laston      { margin:0 0 0 5px; }
.userform .registered  { margin:0 0 0 5px; }
.userform .auth        { margin-left:15px; clear:both; display:none; visibility:hidden; }
.viewform label        { width:160px; }
.viewform .status      { float:right; margin:-10px 10px 0 0; white-space:nowrap; }
.viewform .type        { clear:both; display:none; visibility:hidden; }
.viewform .box         { clear:both; display:none; visibility:hidden; }
.viewform .minical     { clear:both; display:none; visibility:hidden; }
.viewform .type label  { margin-left:20px; }
.viewform .box label   { margin-left:20px; }
.viewform .minical label { margin-left:20px; }
.loginform label       { width:80px; }
.reportdateform label  { width:90px; }

// Header login button
.loginbuttonform { white-space:nowrap; }

// Access profile styles
.authtable    { }
.authtable th { text-align:center; }
.authtable td { padding:0 3px; }



/* =============================================================================
================================ MONTH VIEW ====================================
============================================================================= */

.mtitle    { text-align:center; }
.mtable    { width:100%; border-collapse:collapse; margin:5px; }
.mtable th { width:14%; border-collapse:collapse; padding:3px; font-weight:bold; text-align:center; vertical-align:top; border:none;}
.mtable td { width:14%; height:100px; border:1px solid #AAA; border-collapse:collapse; color:#000; background:#F8F8F8 url(' . $pem_template_path . 'bg-day.png) repeat-x bottom left; vertical-align:top; }
.mtable .mweekend   { background:#' . $color_table[$theme_color][6] . ' url(' . $pem_template_path . 'bg-end.png) repeat-x bottom left; }
.mtable .mspace     { background:#DDD url(' . $pem_template_path . 'bg-space.gif) repeat-x top left; }
.mtable .mhighlight { background-image:none; background-color:#FFF; border:2px solid ' . $border_color . '; vertical-align:top; }
.mtable td:hover        { background:#FFF url(' . $pem_template_path . 'bg-day-on.png) repeat-x top left; }
.mtable .mweekend:hover { background:#FFF url(' . $pem_template_path . 'bg-end-on.png) repeat-x top left; }
.mtable .mspace:hover   { background:#DDD url(' . $pem_template_path . 'bg-space-on2.gif) repeat-x top left; }
.mtable .mdate  { padding:0 2px; float:left; font-weight:bold; font-size:80%; color:#000; background-color:#FFF; border-right:1px solid #CCC; border-bottom:1px solid #CCC; }
.mtable .mevent { clear:left; text-align:left; font-size:70%; line-height:1.2em; margin:0 3px;  }
.mevent:hover { background-color:#F4F4F4; }



body .maddbutton    { float:right; margin:5px 5px 0px 0px; }

body .mnavbox        { width:100%; background-color:#14739E; margin:0px 30px 0px 0px; border-right:1px solid #FFF; clear:left; }
body .mnav           { color:#FFF; font-size:12px; font-weight:bold; font-family:arial,verdana,sans-serif; vertical-align:middle; padding:3px; }
body .mnav a:link    { color:#FFF; font-weight:bold; text-decoration:none; }
body .mnav a:visited { color:#FFF; font-weight:bold; text-decoration:none; }
body .mnav a:active  { color:#FFF; font-weight:bold; text-decoration:underline; }
body .mnav a:hover   { color:#FFF; font-weight:bold; text-decoration:underline; }
body .mnav h2        { color:#FFF; }

.mlistdate          { padding:2px; float:left; font-size:14px; font-weight:bold; font-family:arial,verdana,sans-serif; color:#FFF; }
.mlistdate:link     { color:#FFF; text-decoration:none; }
.mlistdate:visited  { color:#FFF; text-decoration:none; }
.mlistdate:active   { color:#FFF; text-decoration:none; }
.mlistdate:hover    { color:#FFF; text-decoration:none; }
.mlistweekday       { padding:0px; color:#000; background:#FFF url(/images/gradient-day.gif) repeat-x bottom left; border:1px solid #CCC; vertical-align:top; }
.mlistweekend       { padding:0px; color:#000; background:#F7F9FA url(/images/gradient-end.gif) repeat-x bottom left; border:1px solid #CCC; vertical-align:top; }

#sidebar-month     { position:absolute; top:0; right:0px; width:150px; }
#view-month        { margin-right:150px; }

#legend-box        { margin:10px 0 15px 10px; }
#legend-box h4     { text-align:left; font-size:85%; }
#legend-box .key   { width:10px; height:10px; border:1px solid #000; float:left; margin-top:3px; }
#legend-box .label { font-size:80%; font-weight:normal; padding-left:3px;  }



/* =============================================================================
================================ WEEK VIEW ====================================
============================================================================= */

.wtitle    { text-align:center; }
.wtable    { width:100%; border-bottom:1px solid #000; margin:5px; }
.wtable th { width:14%; padding:3px; font-weight:bold; text-align:center; vertical-align:bottom; border-bottom:1px solid #000; }
.wtable td { width:14%; height:100px; border:1px solid #AAA; border-collapse:collapse; color:#000; background:#F8F8F8 url(' . $pem_template_path . 'bg-day.png) repeat-x bottom left; vertical-align:top; }
.wtable .wweekend   { background:#' . $color_table[$theme_color][6] . ' url(' . $pem_template_path . 'bg-end.png) repeat-x bottom left; }
.wtable .whighlight { background-image:none; background-color:#FFF; border:2px solid ' . $border_color . '; vertical-align:top; }
.wtable td:hover        { background:#FFF url(' . $pem_template_path . 'bg-day-on.png) repeat-x top left; }
.wtable .wweekend:hover { background:#FFF url(' . $pem_template_path . 'bg-end-on.png) repeat-x top left; }
.wtable .wdate  { padding:0 2px; float:left; font-weight:bold; font-size:80%; color:#000; background-color:#FFF; border-right:1px solid #CCC; border-bottom:1px solid #CCC; }
.wtable .wevent { clear:left; text-align:left; font-size:70%; line-height:1.2em; margin:0 3px;  }
.wevent:hover { background-color:#F4F4F4; }

body .waddbutton    { float:right; margin:5px 5px 0px 0px; }

#sidebar-week-calendar { float:right; }
#sidebar-week-list     { position:absolute; top:0; right:0px; width:150px; }
#view-week-calendar    { }
#view-week-list        { margin-right:150px; }

#legend-box-calendar        { margin:10px 0 15px 20px; float:left; }
#legend-box-calendar h4     { text-align:left; font-size:85%; }
#legend-box-calendar .key   { width:10px; height:10px; border:1px solid #000; float:left; margin-top:3px; }
#legend-box-calendar .label { font-size:80%; font-weight:normal; padding-left:3px;  }
#legend-box-list        { margin:10px 0 15px 10px; }
#legend-box-list h4     { text-align:left; font-size:85%; }
#legend-box-list .key   { width:10px; height:10px; border:1px solid #000; float:left; margin-top:3px; }
#legend-box-list .label { font-size:80%; font-weight:normal; padding-left:3px;  }






/* =============================================================================
================================ DAY VIEW ======================================
============================================================================= */

.dtitle    { text-align:center; }
.dtable    { width:100%; border-bottom:1px solid #000; margin-bottom:5px; border-collapse:separate;  }
.dtable th { padding:3px; font-weight:bold; text-align:center; vertical-align:bottom; border-bottom:1px solid #000; }
.dtable th.space { border-bottom:none; }
.dtable th.area { border-bottom:none;  }
.dtable td { height:10px; border-right:1px solid #AAA; border-bottom:1px solid #EEE; color:#000; background:#F8F8F8; vertical-align:middle; cursor:pointer; }
.dtable td.devent { text-align:center; font-size:70%; line-height:1.2em; padding:0 3px; background:#FFF; border:2px solid #000; }
.dtable td.unapproved { text-align:center; font-size:70%; line-height:1.2em; padding:0 3px; background:#FFF; border:2px solid ' . $unapproved_color . '; }
.dtable td.private { text-align:center; font-size:70%; font-style:italic; line-height:1.2em; padding:0 3px; background:#FFF; border:2px solid ' . $private_color . '; }
.dtable td.reserved { background:transparent url(' . $pem_template_path . 'bg-reserved.png) repeat top right; cursor:default;  }
.dtable td.dtime { text-align:right; white-space:nowrap; vertical-align:middle; font-size:70%; font-weight:bold; width:80px; padding:0 8px; background:#EEE; margin:0 3px; border-color:#AAA; border-left:1px solid #AAA; cursor:default; }
.dtable td.space { border-width:0 0 0 1px; background:#FFF; cursor:default; }
.devent:hover { background-color:#F4F4F4; }

#sidebar-day-calendar { float:right; }
#sidebar-day-list     { position:relative; clear:both; }
#view-day-calendar    { }
#view-day-list        { }


#legend-box        { margin:10px 0 15px 20px; float:left; }
#legend-box h4     { text-align:left; font-size:85%; }
#legend-box .key   { width:10px; height:10px; border:1px solid #000; float:left; margin-top:3px; }
#legend-box .label { font-size:80%; font-weight:normal; padding-left:3px;  }


/* =============================================================================
================================ LIST VIEW =====================================
============================================================================= */

.ltitle    { text-align:center; }
.ltable    { width:100%; border-collapse:collapse; margin:5px; }
.ltable th { border-collapse:collapse; padding:3px; font-weight:bold; text-align:center; vertical-align:top; border:none;}
.ltable td { border:1px solid #AAA; border-collapse:collapse; color:#000; background:#F8F8F8 url(' . $pem_template_path . 'bg-list-day.png) top left; vertical-align:top; }
.ltable .lweekend   {  background:#' . $color_table[$theme_color][6] . ' url(' . $pem_template_path . 'bg-list-end.png) top left; }
.ltable .lhighlight { background-image:none; background-color:#FFF; border:2px solid ' . $border_color . '; vertical-align:top; }

.lhead  { font-size:100%; font-weight:bold; text-align:center; padding-top:10px; border-bottom:1px solid #CCC; border-bottom:1px solid #CCC; }
.ldate         { color:#000; }
.ldate:link    { color:#000; text-decoration:none; }
.ldate:visited { color:#000; text-decoration:none; }
.ldate:active  { color:#000; text-decoration:none; }
.ldate:hover   { color:#000; text-decoration:none; }
.lnone         { padding:10px 2px 10px 2px; text-align:center; font-size:80%; background-color:#FFF; }
.ltime         { padding-right:5px; float:left; width:180px; text-align:right; font-weight:bold; }
.levent        { clear:left; text-align:left; font-size:70%; margin:0 3px; background-color:#FFF; }
.levent:hover { background-color:#F4F4F4; }

/* =============================================================================
================================ EVENT VIEW ====================================
============================================================================= */

.viewlabel  { font-weight:bold; }

.dateitem { background-color:' . $fieldset_bg . '; margin:0 0 20px 0; }
.dateitemhover { background-color:' . $primary_bg . '; margin:0 0 20px 0; cursor:pointer; }

.cancelledmsg { color:' . $cancelled_color . '; font-weight:bold; text-transform:uppercase; }
.actionmsg { color:' . $action_color . '; font-weight:bold; }


/* =============================================================================
================================ ALL VIEWS ====================================
============================================================================= */

a.unapproved:link    { color:' . $unapproved_color . '; }
a.unapproved:visited { color:' . $unapproved_color . '; }
a.unapproved:active  { color:' . $unapproved_color . '; }
a.unapproved:hover   { color:' . $unapproved_color . '; }
a.private:link    { color:' . $private_color . '; font-style:italic; }
a.private:visited { color:' . $private_color . '; font-style:italic; }
a.private:active  { color:' . $private_color . '; font-style:italic; }
a.private:hover   { color:' . $private_color . '; font-style:italic; }

a.cancelled:link    { font-style:italic; }
a.cancelled:visited { font-style:italic; }
a.cancelled:active  { font-style:italic; }
a.cancelled:hover   { font-style:italic; }

label.unapproved   { color:' . $unapproved_color . '; }
label.private      { color:' . $private_color . '; font-style:italic; }
label.cancelled    { font-style:italic; }

#unscheduled-box    { margin:10px 0 15px 10px; }
#unscheduled-box h4 { text-align:center; font-size:85%; border:1px solid #666; background-color:#AC0D0F; color:#FFF; padding:2px 0; }
#unscheduled-list  { padding-left:0; }
#unscheduled-list li { padding:2px 0 2px 2px; font-size:75%; line-height:1.1em; border-bottom:1px solid #CCC; background-color:#F8F8F8; }
#unscheduled-list a { margin:0px 0; }

.minicalstrip     { margin:0 auto; }
.minicalstrip td  { padding-top:10px; vertical-align:top; padding:0 3px; }
.minicalmonth     { font-size:12px; text-align:center; vertical-align:top; font-weight:bold; }
.minicalmonth a:link     { color:#000; }
.minicalmonth a:visited  { color:#000; }
.minicalmonth a:active   { color:#000; }
.minicalmonth a:hover    { color:#000; }
.minical          { float:left; clear:both; border-collapse:collapse; }
.minical th       { padding:2px; font-size:12px; text-align:center; vertical-align:top; border:1px solid #666; background-color:' . $minical_header_bg . '; color:' . $minical_header_color . '; }
.minical td       { padding:1px; font-size:10px; text-align:center; vertical-align:top; border:1px solid #CCC; border-collapse:collapse; background-color:#F8F8F8; }
.minical .space   { background-color:#DDD; }
.minicalhighlight { padding:2px; font-size:10px; text-align:center; vertical-align:top; border:1px solid #CCC; border-collapse:collapse; background-color:#AC0D0F; color:#FFF; }
.minicalhighlight a:link    { color:#FFF; }
.minicalhighlight a:visited { color:#FFF; }
.minicalhighlight a:active  { color:#FFF; }
.minicalhighlight a:hover   { color:#FFF; }

.minicalendarcurrent { font-weight:bold; }

#sidebar-message { font-size:75%; padding:10px; text-align:left; }

.filterlist { font-size:80%; float:left; margin-right:20px; }


/* =============================================================================
========================== REPORTS AND STATISTICS ==============================
============================================================================= */

.ordivider { width:30px; height:110px; background:transparent url(' . $pem_template_path . 'or.gif) no-repeat top left; float:left; margin:0 10px;}
.datebox { float:left; }
.dateheader { text-align:left; font-weight:bold; background-color:#000; color:#FFF; }

.stattable td { text-align:left; padding:0 2px; }


'; // END CSS
?>