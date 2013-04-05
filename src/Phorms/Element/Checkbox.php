<?php

namespace Phorms;

class Element_Checkbox extends Element_Input {

  // Html field properties
  protected $_input_type = 'checkbox';

  // Should the control default to False if missing
  protected $false_if_missing = True;

  // Set up slightly different attributes
  function getAttributes($data, $prefix) {
    $attributes = parent::getAttributes($data, $prefix);

    $attributes['checked'] = ($attributes['value'] ? True : False);
    $attributes['value'] = '1';

    return $attributes;
  }

  // Co-erce values into true/false
  function getValue($data) {
    $value = parent::getValue($data);

    if ($value === null) {
      if (!$this->false_if_missing) {
        return null;
      }
    }

    return ($value ? True : False);
  }

}
