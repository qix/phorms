<?php

namespace Forms;

class Control_Url extends Control_Input {
  function validate($value) {
    return !!filter_var($value, FILTER_VALIDATE_URL);
  }
}
