<?php

namespace Phorms;

class Element_Url extends Element_Input {
  function validate($value) {
    return !!filter_var($value, FILTER_VALIDATE_URL);
  }
}
