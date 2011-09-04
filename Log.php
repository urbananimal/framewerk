<?php
class framewerk_Log
{
	const EXCEPTION_FILENAME = 'exceptions';
	const ERROR_FILENAME = 'errors';
	const MESSAGE_FILENAME = 'messages';
	
	/**
	 * this function will log an exception
	 */
	public static function logException(Exception $e)
	{
		$err_msg = $e->getMessage();
		$err_file = $e->getFile();
		$err_line = $e->getLine();
		$err_trace = $e->getTraceAsString();
		$err_code = $e->getCode();

		$msg = $err_msg ."\r\n Code: $err_code \r\n File: $err_file \r\n Line: $err_line \r\n Backtrace: \r\n $err_trace";

		self::writeLog($msg, self::EXCEPTION_FILENAME);
	}

	/**
	 * This method is used to log errors
	 */
	public static function logError($message)
	{
		self::writeLog($message, self::ERROR_FILENAME);
	}
	
	/**
	 * This method is used to log general messages.
	 */
	public static function logMessage($message)
	{
		self::writeLog($message, self::MESSAGE_FILENAME);
	}
	
	/**
	 * This method save $message content into a log file.
	 */
	private static function writeLog($message, $filename)
	{
		$time = date("Y-m-d G:i:s");
		$msg = $time .' '. $message."\r\n\r\n";
		$log_path = dirname(__FILE__) . '/../logs/' . APPLICATION_NAME . '/' . $filename;

		error_log($msg, 3, $log_path);
	}
}
?>