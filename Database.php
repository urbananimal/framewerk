<?php
/**
 * A wrapper for PDO that uses properties from the framewerk_Config class to create an active connection to the defined database.
 * 
 * @author John Smith
 * @version 1.0.0
 */
class framewerk_Database extends PDO
{	
    /**
     * PDO integrity constraint violation code, useful for checking a duplicate key exception.
     * <code>
     * if ($e->getCode() == Database::ERROR_INTEGRITY_VIOLATION) echo 'OMG';
     * </code>
     */
    const ERROR_INTEGRITY_VIOLATION = 23000;

	public function __construct($driver_options = array())
	{
		$connection_details = Config::getDbDsnAuth();

		// Create the connection.
		parent::__construct($connection_details['dsn'], $connection_details['username'], $connection_details['password'], Config::getDBDriverOptions() + $driver_options);
	}
}
?>