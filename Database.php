<?php
/**
 * A wrapper for PDO that uses properties from the Framewerk_Config class to create an active connection to the defined database.
 * 
 * @author John Smith
 * @version 1.0.0
 */
class Framewerk_Database extends PDO
{	
    /**
     * PDO integrity constraint violation code, useful for checking a duplicate key exception.
     * <code>
     * if ($e->getCode() == Database::PDO_INTEGRITY_VIOLATION) echo 'OMG';
     * </code>
     */
    const ERROR_INTEGRITY_VIOLATION = 23000;
    
	public function __construct()
	{
		$connection_details = Config::getDbDsnAuth();

		// Create the connection, setting error reporting to throw exceptions
		parent::__construct($connection_details['dsn'], $connection_details['username'], $connection_details['password'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
}
?>