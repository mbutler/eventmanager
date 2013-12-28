<?php
// xulServer.php demonstrates a XUL application with xajax
// XUL will only work in Mozilla based browsers like Firefox
// using xajax version 0.2
// http://xajaxproject.org

require_once("../../xajax_core/xajax.inc.php");

function test() {
        $objResponse = new xajaxResponse();
        $objResponse->alert("hallo");
        $objResponse->assign('testButton','label','Success!');
        return $objResponse;
}

$xajax = new xajax();
$xajax->registerFunction("test");
$xajax->processRequest();
?>