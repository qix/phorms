<?php

namespace Forms;

class Element_Url extends Control_Input {
  function validate($value) {
    return !!filter_var($value, FILTER_VALIDATE_URL);
  }
}
