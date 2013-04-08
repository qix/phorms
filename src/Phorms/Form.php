<?php
namespace Phorms;

class Form extends Container {

  // Fields for action block
  protected $_action = null;
  protected $_method = 'POST';
  protected $_upload = False;

  // Default intent of a submission, for csrf
  protected $_intent = null;

  // Expiry time of the form, used with _csrf field
  protected $_expire = 3600;

  /***
   * On construction build each control
   **/
  function __construct($controls=array(), $properties=array()) {
    parent::__construct($properties);

    foreach ($controls as $key => $control) {
      $control = $this->buildControl($control, $key);

      $this->addElement($control);
    }
  }

  /***
   * Provide default values for some properties
   **/
  function __get($var) {
    $val = parent::__get($var);

    if ($val === null) {
      if ($var == 'action') {
        return Request::getRequestUri();
      }
    }

    return $val;
  }

  /***
   * Convenience method for building controls from arrays
   **/
  function buildControl($control, $key=null) {

    // First check if this is a special type control (@type:caption)
    if (is_string($key) && substr($key, 0, 1) == '@') {

      // Split and try take off a type and caption
      $pieces = explode(':', substr($key, 1), 2);
      $type = array_shift($pieces);
      $caption = array_shift($pieces);

      if (!is_array($control)) {
        throw new Exception('Special controls can only have an array of properties');
      }

      // Fill in the details extracted from the key
      $key = null;
      $control['type'] = $type;
      if ($caption !== null) {
        $control['caption'] = $caption;
      }

    }elseif (is_int($key) && is_string($control) && substr($control, 0, 1) == '@') {
      // Split and try take off a type and caption
      $pieces = explode(':', substr($control, 1), 2);
      $type = array_shift($pieces);
      $caption = array_shift($pieces);

      // Set up a basic control from the two properties
      $control = array(
        'type' => $type,
        'caption' => $caption
      );
    }

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

    return $this->elementFactory($control);
  }

  /***
   * Check if this form was actually submitted (csrf check)
   **/
	function submitted() {
    if (parent::submitted() && isset($_POST['_csrf'])) {
      return Csrf::check($_POST['_csrf'], $this->intent);
    }else{
      return False;
    }
  }

  /***
   * Factory for creating elements from a property array
   **/
  function elementFactory($properties) {
    // Assume control type if it isn't set
    if (!isset($properties['type'])) {
      if (isset($properties['options'])) {
        $properties['type'] = 'select';
      }else{
        $properties['type'] = 'input';
      }
    }

    // Instaniate a Element_{Type} object 
    $class = 'Phorms\\Element_'.$properties['type'];

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
