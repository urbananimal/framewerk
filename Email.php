<?php
/**
 * Email.php
 * 
 * A basic email class to send emails from PHP. Word.
 *
 * @author		John Smith
 * @date		28th April 2009
 * @date		12th August 2010
 * @version		1.0.1
 * @project		Framewerk
 */
class Framewerk_Email
{	
	// The template data avaialble to the email templates.
	private $template_data = array();

	// The subject of the email
	private $subject;

	private $layout_filename;
	// If this an HTML email, this is the template that will be used
	private $template_filename;
	
	private $layout_path;
	private $template_path;


	private $body_text;
	
	private $sender_name;
	private $sender_address;
	
	private $recipient_name;
	private $recipient_address;

	// Can be set by a child class. Will be added to the email's header.
	// Must be formatted correctly! i.e. \r\n
	private $additional_headers = null;
	
	/**
	 * Some boring initilisation tasks, like getting paths
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->template_path = Config::getEmailTemplatePath();
		$this->layout_path = Config::getEmailLayoutPath();
	}

	/**
	 * Public function render an email. Captures the output buffer.
	 */
	public function render()
	{
		ob_start();
		$file_path = ($this->layout_filename ? $this->layout_path.'/'.$this->layout_filename : $this->template_path.'/'.$this->template_filename) . '.tpl.php';
		if(!file_exists($file_path)) throw new Exception('The email template does not exist: ' . $file_path);
		include $file_path;
		// return the output buffer contents and clear it.
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/**
	 * Sets the filename to be included in the body of the email.
	 */
	public function setTemplate($template_filename)
	{	
		$this->template_filename = $template_filename;
	}
	
	public function setLayout($layout_filename)
	{
		$this->layout_filename = $layout_filename;
	}
	
	/**
	 * Set the data that is available to the email templates.
	 */
	public function setTemplateData(array $email_data)
	{
		$this->template_data = $email_data + $this->template_data;
	}
	
	/**
	 * Set the subject of the email.
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}
	
	/**
	 * Alternative to using a template, the email body will be this text.
	 */
	public function setBodyText($body_text)
	{
		$this->body_text = $body_text;
	}
	
	/**
	 * Set the recipients name
	 */
	public function setRecipientName($recipient_name)
	{
		$this->recipient_name = $recipient_name;
	}
	
	/**
	 * Set the recipient's email address
	 * @param $email_address
	 * 
	 * @return boolean
	 */
	public function setRecipientAddress($email_address)
	{
		$this->recipient_address = $email_address;
	}
	
	/**
	 * Set the sender's name
	 */
	public function setSenderName($sender_name)
	{
		$this->sender_name = $sender_name;
	}
	
	/**
	 * Set the sender's address
	 */
	public function setSenderAddress($email_address)
	{
		$this->sender_address = $email_address;
	}
	
	public function send()
	{
		// Construct the email body
		$email_body = $this->template_filename ? $this->render() : $this->body_text;
				
		// Do we have all required params to send an email?
		if(!$this->sender_address) throw new Exception('Sender Address not set.');
		if(!$this->recipient_address) throw new Exception('Email recipient address not set. Make sure you have called setRecipientAddress().');
		if(!$this->subject) throw new Exception('Email subject not set. Make sure you have called setSubject().');
		if(!$email_body) throw new Exception('Email body not set. Have you forgot to call setBodyText() or setTemplate()? Or could not find template or layout - require() failed.');

		// construct the email header				
		$boundary_string = MD5(time().'cool_pants');
			
		$formatted_sender_string = ($this->sender_name) ? '"' . $this->sender_name . '" <' . $this->sender_address . '>' : $this->sender_address;
		$formatted_recipient_string = ($this->recipient_name) ? '"' . $this->recipient_name . '" <' . $this->recipient_address . '>' : $this->recipient_address;
		
		$headers =  "MIME-Version: 1.0\r\n" .
				  	($this->additional_headers ? $this->additional_headers : '') .
				  	"From: $formatted_sender_string\r\n" .
				  	"Content-Type: multipart/alternative; boundary=$boundary_string\r\n";
					
		$message =  "--$boundary_string\r\n" .
					"Content-Type: text/plain; charset=ISO-8859-1\r\n" .
					"Content-Transfer-Encoding: 7bit\r\n\r\n" .
				
					trim(strip_tags(str_replace(array('<br>', '<br/>'), "\r\n", $email_body)))."\r\n\r\n" .
					
					"--$boundary_string\r\n" .
					"Content-Type: text/html; charset=ISO-8859-1\r\n" .
					"Content-Transfer-Encoding: 7bit\r\n\r\n" .
					$email_body."\r\n\r\n".  // Append the message and the email money template.
					"--$boundary_string--";

		// returns true if accepted for delivery, false if it fails
		return mail($formatted_recipient_string, $this->subject, $message, $headers, '-f '.$this->sender_address);
	}

	/**
	 * Convenience method for email templates
	 * 
	 * @param $param_name
	 * @return mixed
	 */
	public function __get($param_name)
	{
		return $this->template_data[$param_name];
	}
	
	public function __isset($param_name)
	{
		return isset($this->template_data[$param_name]);
	}
}
?>