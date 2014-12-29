<?php

class GMailParser {

	/**
	* GMail domains to check in search of aliases.
	*
	* @var array
	*/
	private $domains = array('gmail.com', 'googlemail.com');


	/**
	* Default value to assign to the email basic version.
	*
	* @var string
	*/
	private $default = 'gmail.com';


	/**
	* Verify GMail addresses.
	*
	* if $mail is string it returns boolean;
	* if $mail is array it returns only valid GMail addresses
	*
	* @param mixed $mail
	* @return mixed
	*/
	public function isGmail($mail)
	{
		if(is_array($mail))
			return $this->_parseList($mail, true);

		return $this->_parts($mail, true);
	}


	/**
	* Check an email address or a list of emails,
	* if it is GMail then return the basic address.
	*
	* @param mixed $mail 
	* @return mixed
	*/
	public function parseMail($mail = '')
	{
		if(is_array($mail))
			return $this->_parseList($mail);

		if(empty($mail) || filter_var($mail, FILTER_VALIDATE_EMAIL) === false)
			return false;

		if(($data = $this->_parts($mail)) !== false)
			return $data;

		return false;
	}


	/**
	* Loop arrays.
	*
	* @param array $list
	* @param boolean $bool
	* @return array
	*/
	private function _parseList($array, $bool = false)
	{
		$list = new RecursiveIteratorIterator(new RecursiveArrayIterator($array), false);
		$data = array();

		foreach($list as $mail)
		{
			if($bool === false)
				$data[] = $this->parseMail($mail);

			else
				$data[] = $this->_parts($mail, false, true);
		}

		$result = array_merge(array(), array_filter(array_unique($data)));

		return $result;
	}


	/**
	* Parse mail, can return email or boolean.
	*
	* @param string $str
	* @param boolean $bool
	* @param boolean $strict
	* @return mixed
	*/
	private function _parts($str, $bool = false, $strict = false)
	{
		$isgmail = false;
		$data 	 = sscanf($str, '%[^@]@%s');
		$compose = array();
		list($local_part, $domain_part) = $data;

		if(in_array($domain_part, $this->domains))
		{
			$local_part = str_replace('.', '', $local_part);
			$local_part = strstr($local_part, '+', true) ? : $local_part;
			$pattern 	= '/^([a-zA-Z0-9.]{6,30}+)$/';

			if(preg_match($pattern, $local_part, $match) == 1)
			{
				$isgmail = true;
				$compose = array(
					$local_part, '@', $this->default
				);

				if($bool === true)
					return true;
			}

			else
				return false;
		}

		if($strict === false && $isgmail === false)
		{
			$compose = array(
				$local_part, '@', $domain_part
			);

			if($bool === true)
				return false;
		}

		$compose = array_map('trim', $compose);
		$compose = array_map('mb_strtolower', $compose);

		return implode('', $compose);
	}

}
