--TEST--
Test V8::registerExtension() : Register extension with errors
--SKIPIF--
<?php
require_once(dirname(__FILE__) . '/skipif.inc');

ob_start(NULL, 0, PHP_OUTPUT_HANDLER_CLEANABLE | PHP_OUTPUT_HANDLER_REMOVABLE);
phpinfo(INFO_MODULES);
$minfo = ob_get_contents();
ob_end_clean();

if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
	// On Windows the "Fatal error" happens to appear before the error
	// message output by V8 itself.
	echo "skip Windows";
}

if(preg_match("/V8 Engine Linked Version => (.*)/", $minfo, $matches)) {
    $version = explode('.', $matches[1]);
    if($version[0] < 3 || ($version[0] == 3 && $version[1] < 30)) {
	// old v8 version, has shorter error message and hence doesn't
	// fit our EXCEPTF below
	echo "skip";
    }
}

?>
--FILE--
<?php

$handlebarsJs = "var root = typeof global !== 'undefined' ? global : window, \$Handlebars = 'test';";
echo "-- registerExtension --\n";
V8Js::registerExtension('handlebars', $handlebarsJs, [], true);
echo "-- creating V8Js object --\n";
$v8 = new V8Js();
var_dump($v8);
?>
===EOF===
--EXPECTF--
-- registerExtension --
-- creating V8Js object --
Exception thrown during bootstrapping
Extension or internal compilation error%sin handlebars at line 1.
Error installing extension 'handlebars'.

Fatal error: Uncaught V8JsException: Failed to create V8 context. Check that registered extensions do not have errors. in %s%eextensions_error.php:7
Stack trace:
#0 %s%eextensions_error.php(7): V8Js->__construct()
#1 {main}
  thrown in %s%eextensions_error.php on line 7
