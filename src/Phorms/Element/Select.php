<?php

namespace Phorms;

class Element_Select_Option {
  var $value, $caption, $group;

  function __construct($value, $caption, $group=null) {
    $this->value = $value;
    $this->caption = $caption;
    $this->group = $group;
  }
}

class Element_Select extends Control {
  protected $_options = array();

  // is this a 'multiple' style dropdown?
  protected $_multiple = False;
  // what should the list size be set to
  protected $_size = NULL;
  // prefix printed before control
  protected $_prefix = '';
  // suffix printed before control
  protected $_suffix = '';
  // allow the select box to be empty
  protected $_allow_empty = False;


  /**
   * Settings for when data is posted from external sources
   **/
  // letters to trim off entries
  protected $_trim = NULL;
  // may any alternative values be submitted?
  protected $_alternatives = array();
  // is the dropdown case sensitive
  protected $_case = True;

  static function countOptionLines($options) {
    $last_group = NULL;
    $lines = 0;
    foreach ($options as $option) {
      if ($option->group && $option->group != $last_group) {
        $last_group = $option->group;
        $lines++;
      }
      $lines++;
    }
    return $lines;
  }

  static function renderOptions($options, $default=null, $multiple=False) {
    $output = '';
    $last_group = NULL;
    foreach ($options as $key => $option) {
      if ($option->group && $option->group != $last_group) {
        if ($last_group) $output .= '</optgroup>'."\n";
        $output .= '<optgroup label="'.Html::encode($option->group).'">'."\n";
        $last_group = $option->group;
      }

      // is this option selected?
      $selected = NULL;
      if ($multiple) {
        $selected = ($default && in_array($option->value, $default, True));
      } else {
        $selected = ($default == $option->value);
      }

      $output .= '<option'.Html::attributes(array(
        'value'=>$option->value,
        'selected'=>$selected?'selected':NULL
      )).'>'.Html::encode($option->caption).'</option>'."\n";
    }
    if ($group) $output .= '</optgroup>'."\n";

    return $output;
  }


  // Format a value according to the current options
  function formatValue($value) {
    foreach ($this->options as $option) {
      if (isset($option['key']) && $option['key'] == $value) {
        if (isset($option['caption'])) return $option['caption'];
      }
    }

    return parent::formatValue($value);
  }

  // Override setProperties and convert ->options to objects
	function setProperties($data) {
    if (isset($data['options'])) {
      $options = [];

      foreach ($data['options'] as $key => $option) {
        if (!$option instanceof Element_Select_Option) {
          if (is_string($option)) {
            $option = new Element_Select_Option($key, $option);
          }elseif (is_array($option)) {
            $value = isset($option['value']) ? $option['value'] : $key;
            $caption = isset($option['caption']) ? $option['caption'] : NULL;
            $group = isset($option['group']) ? $option['group'] : NULL;

            // Pull normal indexed options for caption / group
            $index = 0;
            if ($caption === null && isset($option[$index])) $caption = $option[$index++];
            if ($group === null   && isset($option[$index])) $group   = $option[$index++];

            $option = new Element_Select_Option($value, $caption, $group);
          }
        }
        $options[] = $option;
      }

			$data['options'] = $options;
		}

		parent::setProperties($data);

    if ($this->multiple && substr($this->name, -2) == '[]') {
      throw new Exception('Select boxes of type \'multiple\' are automatically named as arrays');
    }
	}

  function getAttributes($value, $prefix='') {
    return array(
      'name'=>$prefix.$this->name.($this->multiple?'[]':''),
      'id'=>$prefix.$this->id,
      'class'=> $this->class,
      'multiple' => $this->multiple,
      'disabled' => $this->disabled,
      'size' => $this->multiple ? ($this->size ?: min(5, self::countOptionLines($this->options))) : NULL,
    );
  }

  /***
   * Converts a value to match the correct case / alternative
   **/
  function convertValue($value) {
    // Without any options no conversion required
    if (!$this->options) {
      return $value;
    }

    if ($value === NULL && !$this->multiple && !$this->optional) {
      $first = array_first($this->options);
      $value = $first['key'];
    }

    if ($this->trim) {
      $value = trim($value, $this->trim);
    }

		if ($value && !$this->case && $this->options) {
      foreach ($this->options as $option) {
        if (strcasecmp(ARR($option, 'key'), $value) == 0) {
          $value = ARR($option, 'key');
        }
      }
		}
		if ($this->alternatives) {
			$cmp = !$this->case ? 'strcasecmp' : 'strcmp';
			foreach ($this->alternatives as $v=>$aka) {
				if ($cmp($v, $value) == 0) { $value = $aka; break; }
			}
		}
    
    // All dashes is equivalent to empty (for required fields)
    if (!$this->multiple) {
      if ($this->optional && $value === NULL) {
        return NULL;
      }elseif (trim($value) == str_repeat('-', strlen(trim($value)))) {
        return '';
      }
    }

    return $value;
  }

	function getValue($values) {
		$value = parent::getValue($values);

    if ($this->multiple && !$value) {
      $value = array();
    }

    // Deal with repeat control results
    if (is_array($value)) {
      foreach ($value as $k => $v) {
        $value[$k] = $this->convertValue($v);
      }
      return $value;
    }else{
      return $this->convertValue($value);
    }
	}

	function validate($value) {

    // Pick a comparison function
    $cmpfn = $this->case ? 'strcmp' : 'strcasecmp';

    foreach ($this->options as $option) {
      if ($cmpfn($option->value, $value) == 0) {
        return parent::validate($value);
      }
    }

    if ($this->alternatives) {
      foreach ($this->alternatives as $alternative=>$aka) {
        if ($cmpfn($value, $alternative) == 0) { 
          return self::validate($aka);
        }
      }
    }

    return False;
	}

	function checkValue($value) {

    if (!$this->options) {
      // If we are allowing empty box, take empty value
      if ($this->allow_empty && !$value) {
        return array();
      }

      return array($this->name => 'The '.strtolower($this->caption ?: ucwords($this->name)).' field does not have any options.');
    }

    if ($this->multiple) {
      if ($this->required && !$value) {
        return array($this->name => 'The '.strtolower($this->caption ?: ucwords($this->name)).' field is required.');
      }

      foreach ($value as $v) {
        if ($errors = parent::checkValue($v)) {
          return $errors;
        }
      }
      return array();
    }else return parent::checkValue($value);
  }
}
