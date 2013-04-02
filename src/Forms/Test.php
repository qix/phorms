<?php

namespace Forms;

class Test {

  static function execute($if, $values) {
    $return = True;
    $join = 'and';

    foreach (preg_split('/ (or|and) /i', $if, NULL, PREG_SPLIT_DELIM_CAPTURE) as $condition) {
      if (!$join) {
        $join = $condition;
        continue;
      }

      if ($condition = trim($condition)) {
        $split = preg_split('/(==|!=|=)/i', $condition, NULL, PREG_SPLIT_DELIM_CAPTURE);
        if (count($split) != 3) return False;

        list($left, $test, $right) = $split;

        // First trim both sides
        $left = trim($left); $right = trim($right);

				// Allow replace [. ] with _, as this can be pre data map
        if (!array_key_exists($values[$left])) {
          $left = str_replace([' ', '.'], '_', $left);
        }

        // Fetch it from the values (or null)
        $left = (array_key_exists($values, $left) ? $values[$left] : null);

        switch ($test) {
        case '=': case '==': $result = $left == $right; break;
        case '!=': $result = $left != $right; break;
        }
      }else $result = True;

      if ($join == 'and') $return = $return && $result;
      elseif ($join == 'or') $return = $return || $result;
      $join = NULL;
    }
    return $return;
  }


}
