<?php

Autoloader::add_core_namespace('Inflame');

Autoloader::add_classes(array(
	'Inflame\\Model'        				=> __DIR__.'/classes/model.php',
	'Inflame\\Record'       				=> __DIR__.'/classes/record.php',
	'Inflame\\Database_Result_Cached'       => __DIR__.'/classes/database/result/cached.php',
));