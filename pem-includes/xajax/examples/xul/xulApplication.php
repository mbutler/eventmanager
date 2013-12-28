<?php
// xulApplication.php demonstrates a XUL application with xajax
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

header("Content-Type: application/vnd.mozilla.xul+xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
echo "<?xml-stylesheet href=\"chrome://global/skin/\" type=\"text/css\"?>";
?>
<window id="example-window" title="Exemple 2.2.1"
        xmlns:html="http://www.w3.org/1999/xhtml"
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul">
    <script type="application/x-javascript">
		var xajaxConfig = {
			requestURI: "xulServer.php",
			debug: false,
			statusMessages: false,
			waitCursor: true,
			version: "xajax v0.5 alpha"
			};
	</script>
	<script type="application/x-javascript" src="../../xajax_js/xajax.js"></script>
    <button id="testButton" oncommand="xajax.call('test',[]);" label="Test" />
</window>