<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

require("global.inc.php");

define("PATH_CLASSES",
	dirname(__FILE__)
	.DIRECTORY_SEPARATOR
	."classes"
	.DIRECTORY_SEPARATOR
);

define("PATH_LIB",'D:/wamp/www/lib/');

set_include_path(
		// current path
		get_include_path() .PATH_SEPARATOR
		.PATH_CLASSES .DIRECTORY_SEPARATOR ."xml" .PATH_SEPARATOR
		.PATH_CLASSES .DIRECTORY_SEPARATOR ."processors" .PATH_SEPARATOR
		.PATH_CLASSES .DIRECTORY_SEPARATOR ."decoder" .PATH_SEPARATOR
);
?>