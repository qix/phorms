<?php

namespace Forms;

/***
 * Abstract class for Forms components that contain other components
 **/
abstract class Container extends Base {
	protected $elements = array();

  protected $_prefix = null;
  protected $_fill = null;
  protected $_check = null;

  /***
   * Strip the prefix from given values, returning only matches
   **/
  private static function stripPrefix($values, $prefix) {
    $return = array();
    $L = strlen($prefix);
    foreach ($values as $k => $v) {
      if (substr($k, 0, $L) === $prefix) {
        $return[substr($k,$L)] = $v;
      }
    }
    return $return;
  }

  /***
   * Add a prefix to given values
   **/
  private static function addPrefix($values, $prefix) {
    $return = array();
    foreach ($values as $k=>$v) {
      $return[$prefix.$k] = $v;
    }
    return $return;
  }

  /***
   * Recursively join data arrays
   **/
  static function recursiveMerge(/* ... */) {
    $merged = array();
    foreach (func_get_args() as $array) {
      if (!is_array($array)) throw new Exception('recursiveMerge only accepts array parameters');

      if (!$merged) $merged = $array;
      else{
        foreach ($array as $k => $v) {
          if (is_array($v) && isset($merged[$k]) && is_array($merged[$k])) {
            $merged[$k] = self::recursiveMerge($merged[$k], $v);
          }else{
            $merged[$k] = $v;
          }
        }
      }
    }
    return $merged;
  }

	function data($values=True) {

    // Take values from $_POST if set to True
    if ($values === True) {
      $values = $_POST;
    }

    // First check this objects test
    if (!$this->test($values)) {
      return array();
    }

    // Strip out the prefix if it exists
    if ($this->prefix) {
      $values = self::stripPrefix($values, $this->prefix);
    }

    // Fetch data from all the sub-controls
		$res = array();
	  foreach ($this->elements as $element) {
      $res = self::recursiveMerge($res, $element->data($values));
	  }

		if ($fn = $this->fill) {
      $fn($res);
    }

    // If this form has a name, return all components as a 
    if ($this->name) {
      return $this->returnData($res);
    }else{
      return $res;
    }
	}

	function check($data) {
    if (!$this->test($data)) {
      return [];
    }

    // Remove data from name group if possible
    if ($this->name) {
      if (isset($data[$this->name])) {
        $data = $data[$this->name];
      }else{
        $data = [];
      }
    }

		$errors = array();
		foreach ($this->elements as $element) {
      $element_errors = $element->check($data);
      if (!is_array($element_errors)) {
        throw new Exception('Element control did not return an array of errors');
      }

      $errors = self::recursiveMerge($errors, $element_errors);
		}
		if ($fn = $this->check) {
			$errors = self::recursiveMerge($errors, $fn($data, $errors));
		}

    if ($this->prefix) {
      $errors = self::addPrefix($errors, $this->prefix);
    }

		return $errors;
	}

  /***
   * Element controls
   **/
  function getElements() {
    return $this->elements;
  }

  function addElement(Element $element) {
    $this->elements[] = $element;
  }

	function getElement($name) {
		foreach ($this->elements as $element) {
			if ($element instanceof Element) {
        if ($return = $element->getElement($name)) {
          return $return;
        }
			}
		}
    return NULL;
	}

  function queryElements($selector) {
    $results = [];

		foreach ($this->elements as $element) {
			if ($element instanceof Element) {
        $results = array_append($results,
          $element->queryElements($selector)
        );
			}
		}

    return $results;
  }

	function removeElement($remove) {
		foreach ($this->elements as $key => $element) {
			if ($element instanceof Element) {
        if ($remove == $element) {
          unset($this->elements[$key]);
          return $element;
        }elseif ($return = $element->removeElement($remove)) {
          // Remove it if it returned itself
          if ($element == $return) {
            unset($this->elements[$key]);
          }
          return $return;
        }
			}
		}
    return NULL;
	}

	function replaceElement($replace_element, $with) {
    if ($replace_element == $this) {
      throw new Exception('Root group cannot be replaced.');
    }

		foreach ($this->elements as $key => $element) {
			if ($element instanceof Element) {
        if ($element == $replace_element) {
          $this->elements[$key] = $with;
          return True;
        }else{
          if ($element->replaceElement($replace_element, $with)) {
            return True;
          }
        }
      }
    }
    return False;
	}
}

