<?php
class Validate
{

	// Error array
	private $errors	= [];

	// Source array
	private $source	= [];

	// Rules array
	private $rules	= [];

	// Result array
	private $result	= [];

	// Contrucst
	public function __construct($source)
	{
		$this->source = $source;
	}

	// Add rules
	public function addRules($rules)
	{
		$this->rules = array_merge($rules, $this->rules);
	}

	// Get error
	public function getError()
	{
		return $this->errors;
	}

	// Set error
	public function setError($element, $message)
	{

		if (array_key_exists($element, $this->errors)) {
			$this->errors[$element] .= ' - ' . $message;
		} else {
			$this->errors[$element] = '<b>' . ucfirst($element) . ':</b> ' . $message;
		}
	}

	// Get result
	public function getResult()
	{
		return $this->result;
	}

	// Add rule
	public function addRule($element, $type, $options = null, $required = true)
	{
		$this->rules[$element] = array('type' => $type, 'options' => $options, 'required' => $required);
		return $this;
	}

	// Run
	public function run()
	{
		foreach ($this->rules as $element => $value) {
			if ($value['required'] == true && trim($this->source[$element]) == null) {
				$this->setError($element, 'is not empty');
			} else {
				switch ($value['type']) {
					case 'special':
						$this->validateSpecial($element, $value['options']['deny']);
						break;
					case 'category':
						$this->validateCategory($element, $value['options']['deny']);
						break;
					case 'user':
						$this->validateUser($element, $value['options']['deny']);
						break;
					case 'int':
						$this->validateInt($element, $value['options']['min'], $value['options']['max']);
						break;
					case 'string':
						$this->validateString($element, $value['options']['min'], $value['options']['max']);
						break;
					case 'url':
						$this->validateUrl($element);
						break;
					case 'email':
						$this->validateEmail($element);
						break;
					case 'status':
						$this->validateStatus($element, $value['options']['deny']);
						break;
					case 'group':
						$this->validateGroupID($element);
						break;
					case 'password':
						$this->validatePassword($element, $value['options']);
						break;
					case 'date':
						$this->validateDate($element, $value['options']['start'], $value['options']['end']);
						break;
					case 'existRecord':
						$this->validateExistRecord($element, $value['options']);
						break;
					case 'isExistRecord':
						$this->validateIsExistRecord($element, $value['options']);
						break;
					case 'file':
						$this->validateFile($element, $value['options']);
						break;
				}
			}
			if (!array_key_exists($element, $this->errors)) {
				$this->result[$element] = $this->source[$element];
			}
		}
		$eleNotValidate = array_diff_key($this->source, $this->errors);
		$this->result	= array_merge($this->result, $eleNotValidate);
	}

	// Validate Integer
	private function validateInt($element, $min = 0, $max = 0)
	{
		if (!filter_var($this->source[$element], FILTER_VALIDATE_INT, array("options" => array("min_range" => $min, "max_range" => $max)))) {
			$this->setError($element, 'is an invalid number');
		}
	}

	// Validate String
	private function validateString($element, $min = 0, $max = 0)
	{
		$length = strlen($this->source[$element]);
		if ($length < $min) {
			$this->setError($element, 'is too short');
		} elseif ($length > $max) {
			$this->setError($element, 'is too long');
		} elseif (!is_string($this->source[$element])) {
			$this->setError($element, 'is an invalid string');
		}
	}

	// Validate URL
	private function validateURL($element)
	{
		if (!filter_var($this->source[$element], FILTER_VALIDATE_URL)) {
			$this->setError($element, 'is an invalid url');
		}
	}

	// Validate Email
	private function validateEmail($element)
	{
		if (!filter_var($this->source[$element], FILTER_VALIDATE_EMAIL)) {
			$this->setError($element, 'is an invalid email');
		}
	}

	public function showErrors()
	{
		$xhtml = '';
		if (!empty($this->errors)) {
			$xhtml .= '
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<ul class="list-unstyled">
			';
			foreach ($this->errors as $key => $value) {
				$xhtml .= sprintf('<li>%s</li><br>', $value);
			}
			$xhtml .= '</ul></div>';
		}
		return $xhtml;
	}

	public function isValid()
	{
		if (count($this->errors) > 0) return false;
		return true;
	}

	// Validate Status
	private function validateStatus($element, $deny)
	{
		if (in_array($this->source[$element], $deny)) {
			$this->setError($element, 'Select status');
		}
	}

	// Validate Category
	private function validateCategory($element, $deny)
	{
		if (in_array($this->source[$element], $deny)) {
			$this->setError($element, 'Select Category');
		}
	}

	// Validate Category
	private function validateSpecial($element, $deny)
	{
		if (in_array($this->source[$element], $deny)) {
			$this->setError($element, 'Select Special');
		}
	}

	private function validateUser($element, $deny)
	{
		if (in_array($this->source[$element], $deny)) {
			$this->setError($element, 'is exist');
		}
	}

	// Validate GroupID
	private function validateGroupID($element)
	{
		if ($this->source[$element] == 0) {
			$this->setError($element, 'Select group');
		}
	}

	// Validate Password
	private function validatePassword($element, $options)
	{
		if ($options['action'] == 'add' || ($options['action'] == 'edit' && $this->source[$element])) {
			$pattern = '#^(?=.*\d)(?=.*[A-Z])(?=.*\W).{8,8}$#';	// Php4567!
			if (!preg_match($pattern, $this->source[$element])) {
				$this->setError($element, 'is an invalid password');
			};
		}
	}

	// Validate Date
	private function validateDate($element, $start, $end)
	{
		// Start
		$arrDateStart 	= date_parse_from_format('d/m/Y', $start);
		$tsStart		= mktime(0, 0, 0, $arrDateStart['month'], $arrDateStart['day'], $arrDateStart['year']);

		// End
		$arrDateEnd 	= date_parse_from_format('d/m/Y', $end);
		$tsEnd			= mktime(0, 0, 0, $arrDateEnd['month'], $arrDateEnd['day'], $arrDateEnd['year']);

		// Current
		$arrDateCurrent	= date_parse_from_format('d/m/Y', $this->source[$element]);
		$tsCurrent		= mktime(0, 0, 0, $arrDateCurrent['month'], $arrDateCurrent['day'], $arrDateCurrent['year']);

		if ($tsCurrent < $tsStart || $tsCurrent > $tsEnd) {
			$this->setError($element, 'is an invalid date');
		}
	}

	// Validate Exist record
	private function validateExistRecord($element, $options)
	{
		$database = $options['database'];

		$query	  = $options['query'];
		if ($database->isExist($query) == false) {
			$this->setError($element, 'record is not exist');
		}
	}

	private function validateIsExistRecord($element, $options)
	{
		$database = $options['database'];

		$query	  = $options['query'];
		if ($database->isExist($query) == true) {
			$this->setError($element, 'record is exist');
		}
	}

	// Validate File
	private function validateFile($element, $options)
	{

		if (!filter_var($this->source[$element]['size'], FILTER_VALIDATE_INT, array("options" => array("min_range" => $options['min'], "max_range" => $options['max'])))) {
			$this->setError($element, 'kích thước không phù hợp');
		}

		$ext = pathinfo($this->source[$element]['name'], PATHINFO_EXTENSION);
		if (in_array($ext, $options['extension']) == false) {
			$this->setError($element, 'phần mở rộng không phù hợp');
		}
	}

	
}
