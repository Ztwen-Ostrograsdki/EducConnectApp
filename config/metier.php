<?php


return [

	'interro_percentage' => 0.3,
    
	'dev_percentage' => 0.7,

	'percentage_to_be_declared_successed' => (float) env('METIER_PERCENTAGE_SUCCESS', 50),

	'default_module_limit' => (float) env('METIER_DEFAULT_MODULE_LIMIT', 10),

];