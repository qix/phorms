<?php

namespace Forms;

abstract class Control extends Base {
  // general type/class of control
  protected $_type = null;
  // html 'name' field
  protected $_name = null;
  // error message associated with control (or null)
  protected $_error = null;
  // caption associated with control (or null)
  protected $_caption = null;
  // short hint/tip for control (or null)
  protected $_tip = null;
  // default value (or null)
  protected $_default = NULL;
  // is a value required for the control
  protected $_required = False;
  // is the control currently disabled
  protected $_disabled = False;
  // is the control constant/readonly
  protected $_constant = False;
  // can the control be entirely left out?
  protected $_optional = False;
  // can this control be validated against a strict regex
  protected $_regex = null;
  // format explaining the regex (for validation fail)
  protected $_format = null;

  //-- Special fields (can be generated if not provided) 
  //
  // array of css classes to apply
  protected $_class = [];
  // html 'id' field, built from name if not provided
  protected $_id = null;


  /**
   * Add a CSS class to this control.
   *
   * Takes either a string class, or an array of classes to add
   */
	function addClass($class) {
    if (is_array($class)) {
      array_map([$this, 'addClass'], $class);
    }elseif (is_string($class)) {
      $this->_class[] = $class;
    }else{
      throw new Exception('addClass must be passed either a single string class, or an array of classes.');
    }
	}

  /**
   * Standard validate method using a regex if one was provided
   **/
	function validate($value) {
		if ($this->regex && !preg_match($this->regex, $value)) {
			return False;
		}
		return True;
	}

  /***
   * Checks a single scalar (bool/int/string/...) value is correct
   * @returns map of control name to error
   **/
	function checkValue($value) {
    $error_message = null;

    if (!is_scalar($value) && $value !== NULL) {
			$error_message = 'The %s field was not of the correct type.';
    }elseif ($this->required && trim($value) === '') {
			$error_message = 'The %s field is required.';
		}elseif ($value && !$this->validate($value)) {
      $error_message = 'The %s field '.($this->format ?: 'was not valid');
    }
    
    if ($error_message) {
      return array($this->name => sprintf($error_message, strtolower($this->caption)));
    }else return array();
	}

  /**
   * Automatically generate some values if they are NULL
   **/
  function __get($var) {
    $value = parent::__get($var);

    if ($value === NULL) {
      switch ($var) {
      case 'caption':
        $name = $this->name;
        if ($name !== null) {
          $value = ucwords($this->name);
        }
        break;
      }
    }

    return $value;
  }

  /**
   * Check this control against supplied values
   **/
	function check($values) {
    if (!$this->test($values)) return array(); 

    $check = $this->getValue($values);

    // Do not check empty optional controls
    if ($this->optional && $value === NULL) {
      return array();
    }

    if (substr($this->name, -2) == '[]') {
      // If it ends with [.*] treat as an array of values
      if (!is_array($check)) {
        return array($this->name => 'The '.strtolower($this->caption ?: ucwords($this->name)).' field must be a list of values');
      }elseif (!$check) {
        if ($this->required) {
          return array($this->name => 'The '.strtolower($this->caption ?: ucwords($this->name)).' field is required.');
        }else{
          return array();
        }
      }else{
        foreach ($check as $value) {
          if ($errors = $this->checkValue($value)) {
            return $errors;
          }
        }
        return array();
      }
    }else{
      return $this->checkValue($check);
    }
	}

  function returnData($value) {
    if ($this->optional && $value === null) {
      return array();
    }else{
      return parent::returnData($value);
    }
  }

  /**
   * @return boolean Whether control has given CSS class
   */
  function has_class($class) {
    return in_array($class, $this->class);
  }
}

