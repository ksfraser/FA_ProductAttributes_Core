<?php

//namespace Ksfraser\FA;

/**//************************************
* Load the SQL file for the module
*
*	As this is a FrontAccounting module, assumption is
*	that the sql file will be in the sql/ directory 
*	of this module.
*
* @since 20260208
*/
class createDatabaseSchema
{
	private $module_path;
	private $schema_file;
	function __construct( $module_path, $filename = "schema.sql" )
	{
		$this->module_path = $module_path;
		$this->schema_file = $this->module_path . '/sql/' . $filename;
        	if (!file_exists($this->schema_file)) {
            		throw new Exception('Schema file not found: ' . $this->schema_file);
		}

        	$sql = file_get_contents($this->schema_file);
        	if ($sql === false) {
            		throw new Exception('Failed to read schema file: ' . $schema_file);
        	}

		// Split SQL into individual statements
        	$statements = array_filter(array_map('trim', explode(';', $sql)));

        	global $db;
        	foreach ($statements as $statement) 
		{
			if (!empty($statement)) 
			{
				$result = db_query($statement, 'Failed to execute schema statement');
				if (!$result) 
				{
					throw new Exception('Failed to execute schema statement: ' . $statement);
				}
			}
		}
	}
}