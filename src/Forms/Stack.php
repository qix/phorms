<?php

namespace Forms;

abstract class Stack extends Base {
  // general type/class of the stack item
  protected $_type = null;

  function render($data, $prefix='') {}
}
