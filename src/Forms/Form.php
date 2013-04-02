<?php
namespace Forms;

class Form extends Container {

  function __construct($controls=array(), $properties=array()) {
    parent::__construct($properties);

    foreach ($controls as $key => $control) {
      $control = $this->buildControl($control, $key);

      $this->addElement($control);
    }
  }

  /***
   * Convenience method for building controls from arrays
   **/
  function buildControl($control, $key=null) {

    if (is_string($control)) {
      $control = [$control];
    }elseif ($control instanceof Element) {
      return $control;
    }elseif (!is_array($control)) {
      throw Exception('Controls can only be built from strings, arrays, or elements.');
    }

    // Add the key (if string) to the options array
    if (is_string($key)) {
      array_unshift($control, $key);
    }

    // Convert all numeric properties into one of the following
    // name, caption, options (if array)
    foreach ($control as $key => $value) {
      if (is_numeric($key)) {
        if (!isset($control['name'])) {
          $control['name'] = $value;
        }elseif (!isset($control['caption'])) {
          $control['caption'] = $value;
        }elseif (!isset($control['options']) && is_array($value)) {
          $control['options'] = $value;
        }else{
          throw new Exception('Could not automatically determine property from array');
        }

        // Safe with PHP foreach iteration
        unset($control[$key]);
      }
    }

    return $this->controlFactory($control);
  }

  /***
   * Factory for creating controls from a property array
   **/
  function controlFactory($properties) {
    // Assume control type if it isn't set
    if (!isset($properties['type'])) {
      if (isset($properties['options'])) {
        $properties['type'] = 'select';
      }else{
        $properties['type'] = 'input';
      }
    }

    // Instaniate a Control_{Type} object 
    $class = 'Forms\\Control_'.$properties['type'];

    // Run ucwords with ' ' instead of '_'
    $class = str_replace(' ', '_', ucwords(str_replace('_', ' ', $class)));

    return new $class($properties);
  }

  /***
   * Factory for creating the default renderer
   **/
  function rendererFactory() {
    return new Renderer();
  }

  /***
   * Render the form with the given renderer
   **/
  function renderWith($renderer, $data, $prefix='') {
    $renderer->render($this, $data, $prefix);
  }

  /***
   * Render the form with the default renderer
   **/
	function render($data, $prefix='') {
    $renderer = $this->rendererFactory();
		$this->renderWith($renderer, $data, $prefix);
	}

}
