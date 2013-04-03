<?php

namespace Forms;

class Element_Email extends Element_Input {
  function validate($value) {
    return !!filter_var($value, FILTER_VALIDATE_EMAIL);
  }
}
