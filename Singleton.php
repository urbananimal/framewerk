<?php
/**
 * A reusable singleton class. Can be used by any class.
 * 
 * @author John Smith
 * @version 1.0.5
 *
 */
class Framewerk_Singleton
{
	protected static $instances = array(); // Stores all current instances

	/**
	 * The instance name is the class name, concatenated with all it's contructor arguments.
	 * This allows a new instance to be created, when the arguments are different.
	 * 
	 * @param string $class_name - the name of the class to instantiate.
	 * @return instanceof $class_name
	 **/	
	public static function getInstance($class_name)
	{
		# Does an instance of this object already exist?
		if(!isset(self::$instances[$class_name]))
		{
			# create a new instance
			self::$instances[$class_name] = new $class_name;
		}
		
		return self::$instances[$class_name];
	}
}
?>