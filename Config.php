<?php
interface framewerk_Config
{
	const ENV_DEV = 0;
	const ENV_STAGE = 1;
	const ENV_LIVE = 2;

	/**
	 * Should return one of
	 * 	ConfigInterface::ENV_DEV
	 *	ConfigInterface::ENV_STAGE
	 * 	ConfigInterface::ENV_LIVE
	 * 
	 * @return integer
	 */
	public static function getEnvironment();

	public static function getDefaultController();

	public static function getCssDirectory();

	public static function getJsDirectory();

	/**
	 * Should be the absolute path the root of the application.
	 * Like everything else, no trailing slash.
	 * 
	 * @return string
	 */
	public static function getApplicationRoot();

	/**
	 * Return an array consisting of DSN (data source name), username and password
	 * 
	 * @return array
	 */
	public static function getDbDsnAuth();
	
	/**
	 * Return a key=>value array of PDO driver-specific connection options.
	 * 
	 * @return array
	 */
	public static function getDBDriverOptions();

	/**
	 * Return the absolute path to ViewHtml templates
	 * 
	 * @return string
	 */
	public static function getTemplatePath();

	/**
	 * Return the absolute path the ViewHtml Layouts
	 * 
	 * @return string
	 */
	public static function getLayoutPath();

	/**
	 * Return the absolute path to the Element templates
	 */
	public static function getElementPath();

	/**
	 * Return the absolute path to email templates
	 * 
	 * @return string
	 */
	public static function getEmailTemplatePath();

	/**
	 * Return the absolute path to email layouts
	 * 
	 * @return string
	 */
	public static function getEmailLayoutPath();

	public static function getSiteUrl();

	/**
	 * Should return NULL unless you want to force a specififc router.
	 * Routers can be selected from framewerk_RouterFactory::TYPE...
	 */
	public static function getRouter();

	/**
	 * Return NULL unless you want to force a specific type of view.
	 * Views can be selected from framewerk_ViewFactory::TYPE...
	 */
	public static function getView();
}
?>