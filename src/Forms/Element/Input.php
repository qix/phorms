<?php

namespace Forms;

class Element_Input extends Control {

  // Html field properties
  protected $_input_type = 'input';
  protected $_size = null;
  protected $_maxlength = null;

  // Some flags
  protected $_disabled     = False;
  protected $_readonly     = False;
  protected $_autocomplete = True;

  // Prefix and suffix text
  protected $_prefix = '';
  protected $_suffix = '';

  function getAttributes($data, $prefix) {
    $value = $this->getValue($data);

    return array(
      'name' => $prefix.$this->name,
      'class' => $this->class,
      'size' => $this->size,
      'id' => $prefix.$this->id,
      'type' => $this->input_type,
      'value' => is_array($value) ? NULL : $value,
      'disabled' => $this->disabled,
      'readonly' => $this->readonly,
      'autocomplete' => $this->autocomplete,
      'maxlength' => $this->maxlength,
    );
  }

}
