<?php

namespace Forms;

/***
 * Abstract class for Forms components that contain other components
 **/
abstract class Container extends Base {
	protected $controls = array();

  function getControls() {
    return $this->controls;
  }

	function getControl($name) {
		foreach ($this->controls as $control) {
			if ($control instanceof Element) {
        if ($return = $control->getControl($name)) {
          return $return;
        }
			}
		}
    return NULL;
	}

  function queryControls($selector) {
    $results = [];

		foreach ($this->controls as $control) {
			if ($control instanceof Element) {
        $results = array_append($results,
          $control->queryControls($selector)
        );
			}
		}

    return $results;
  }

	function removeControl($remove) {
		foreach ($this->controls as $key => $control) {
			if ($control instanceof Element) {
        if ($remove == $control) {
          unset($this->controls[$key]);
          return $control;
        }elseif ($return = $control->removeControl($remove)) {
          // Remove it if it returned itself
          if ($control == $return) {
            unset($this->controls[$key]);
          }
          return $return;
        }
			}
		}
    return NULL;
	}

	function replaceControl($replace_control, $with) {
    if ($replace_control == $this) {
      throw new Exception('Root group cannot be replaced.');
    }

		foreach ($this->controls as $key => $control) {
			if ($control instanceof Element) {
        if ($control == $replace_control) {
          $this->controls[$key] = $with;
          return True;
        }else{
          if ($control->replaceControl($replace_control, $with)) {
            return True;
          }
        }
      }
    }
    return False;
	}
}

