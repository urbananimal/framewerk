<?php
/**
 * 
 * A class to set and get notices. Uses $_SESSION so they persist over redirects.
 * I was going to put this logic in the view. But I didn't.
 * 
 * @author John
 */
class framewerk_Notice
{
	const TYPE_ERROR = 0;
	const TYPE_SUCCESS = 1;

	const NOTICE_SESSION_NAME = 'NOTICES';

	public static function setNotice($notice, $notice_type, $identifier)
	{
		$_SESSION[self::NOTICE_SESSION_NAME][$notice_type][] = array('notice' => $notice, 'identifier' => $identifier);
	}

	/**
	 *
	 * @param $notice_type
	 * @return array
	 */
	public static function getNotices($notice_type)
	{
		if(!isset($_SESSION[self::NOTICE_SESSION_NAME])) return array();
		if(!isset($_SESSION[self::NOTICE_SESSION_NAME][$notice_type])) return array();

		$notices = $_SESSION[self::NOTICE_SESSION_NAME][$notice_type];

		unset($_SESSION[self::NOTICE_SESSION_NAME][$notice_type]);

		// Reverse array, so most recently set notice is printed first
		return array_reverse($notices);
	}
}
?>