<?php

namespace Forms;

/***
 * Abstract class for Forms components that contain other components
 **/
abstract class Container extends Base {
	protected $elements = array();

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

