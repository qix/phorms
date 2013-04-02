<?php

namespace Forms;

/***
 * An exception class that includes additional context
 **/
class ContextException extends Exception {
  protected $context = null;

  function __construct($message='', $context=array(), $code=0, $previous=null) {
    parent::__construct($message, $code, $previous);

    $this->context = $context;
  }

  function getContext($key=NULL) {
    if ($key !== NULL) {
      return isset($this->context[$key]) ? $this->context[$key] : NULL;
    }else return $this->context;
  }
}
