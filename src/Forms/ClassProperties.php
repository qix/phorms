<?php

namespace Forms;

class ClassProperties {

  function setProperties($properties) {
    foreach ($properties as $k=>$v) {
      if (property_exists($this, "_$k")) {
        if (property_exists($this, "$k")) {
          throw new ContextException('Both _-prefixed and normal variables are available', array(
            'object' => $this,
            'variable' => $k
          ));
        }
        $k = '_'.$k;
        $this->$k = $v;
      }else{
        // Only accept unknown properties if they are null
        if ($v !== null) {
          throw new MissingPropertyException('Unknown class property: '.$k, array(
            'property' => $k,
            'value' => $v,
            'object' => $this
          ));
        }
      }
    }
  }

  function __get($var) {
    if (property_exists($this, '_'.$var)) {
      $var = "_$var";
      return $this->$var;
    }else{
      throw new MissingVariableException($var, $this);
    }
  }

  function __isset($var) {
    if (property_exists($this, $var)) {
      return True;
    }else{
      return False;
    }
  }

  function __set($var, $val) {
    $this->setProperties(array($var => $val));
  }
}


