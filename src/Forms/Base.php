<?php

namespace Forms;

/***
 * Base class for all standard Forms elements
 **/
abstract class Base extends ClassProperties implements Element {
  protected $_type = null;
  protected $_name = null;
  protected $_id = null;
  protected $_class = [];
  protected $_if = null;

  // additional fields for getValue
  protected $_value = null;
  protected $_default = null;

  /**
   * Automatically set options on construct
   **/
	function __construct($properties=array()) {
		$this->setProperties($properties);
	}

  protected function sanitizeProperties($options) {
    if (isset($options['class'])) {
      if (!is_array($options['class'])) {
        $options['class'] = array($options['class']);
      }else{
        $options['class'] = array_flatten($options['class']);
      }
    }
    return $options;
  }

	function setProperties($options) {
    $options = $this->sanitizeProperties($options);
    parent::setProperties($options);
	}

  /**
   * Provide a default ->data() if $name is set
   **/
  function data($values) {
    if ($this->name && $this->test($values)) {
      return $this->returnData(
        $this->getValue($values)
      );
    }
    return [];
  }

  /**
   * Run a transform of a single value
   * (even when used in name[] controls)
   **/
  function transformValue($value) {
    return $value;
  }

  /**
   * Retrieve the value from the posted array
   **/
	function getValue($post) {
    $isArray = ends_with($this->name, '[]');
    $underscores = str_replace('.','_',$this->name);

    // Pick the first non-null result
    // Use ARR for array behaviour
    $value = $this->_value;

    if ($value === null) $value = ARR($post, $this->name);

    // Array variables equal to [] are equivalent to null
    //  (checking for underscore name will reset to [])
    if ($isArray && $value === []) $value = null;

    if ($value === null) $value = ARR($post, $underscores);
    if ($value === null) $value = $this->default;

    // Run the transformValue on the values
    if ($isArray) {
      return array_map([$this, 'transformValue'], $value);
    }else{
      return $this->transformValue($value);
    }
	}

  /**
   * __get / __isset for values out of properties
   **/
  function __get($var) {
    if ($var == 'id' && $this->_id === null) {
      $name = $this->name;

      // Replace '.' and ' ' with '_' for id's
      if ($name !== null) {
        $name = str_replace(array('.',' '),'_',$name);
      }

      return $name;
    }elseif ($var == 'value') {
      throw new Exception('value is not accessible directly; use getValue rather');
    }else{
      return parent::__get($var);
    }
  }

  /**
   * If an 'if' is provided, use it as a test function
   **/
  function test($values) {
    if ($this->if !== null) {
      return Test::execute($this->if, $values);
    }
    return True;
  }

  /**
   * Shortcut for returning data for this field
   **/
  function returnData($value) {
    if (!$this->name) {
      throw new Exception('returnData can only be called on Base with name');
    }

    return array($this->name => $value);
  }

  /**
   * Removes a control given its name
   *
   * Returns itself if it must be removed
   **/
  function removeElement($name) {
    if ($this->name === $name) {
      return $this;
    }else{
      return NULL;
    }
  }

  /**
   * Replaces a control given an object
   **/
  function replaceElement($replace_control, $with) {
    if ($this == $replace_control) {
      throw new Exception('Root control cannot be replaced.');
    }
    return False;
  }

  /**
   * Searches all controls by a given selector
   *
   * @TODO: This should accept CSS style selectors eventually
   **/
  function queryElements($selector) {
    if ($selector == '*' || $this->type == $selector) {
      return [$this];
    }else return [];
  }

  /**
   * Provide default getElement method to return itself
   **/
  function getElement($name) {
    if ($this->name === $name) {
      return $this;
    }else{
      return NULL;
    }
  }

  /**
   * Provide default names based on this->name
   **/
  function names() {
    return $this->name ? array($this->name) : array();
  }

  /**
   * Provide empty check and error functions
   **/
  function check($data) { return []; }
  function errors($errors) { return []; }
}

