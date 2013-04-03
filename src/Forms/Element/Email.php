<?php

namespace Forms;

class Control_Email extends Control_Input {
  function validate($value) {
    return !!filter_var($value, FILTER_VALIDATE_EMAIL);
  }
}
