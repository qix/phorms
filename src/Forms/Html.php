<?php

namespace Forms;

/***
 * Helper methods for HTML generation
 **/
class Html {
  static function encode($value, $newlines=False) {
    $html = htmlentities(strval($value),ENT_QUOTES|ENT_IGNORE,'UTF-8');
    if ($newlines) {
      $html = str_replace(array("\r\n", "\n"), '<br>', $html);
    }
    return $html;
  }

  static function attributes($attrib) {
    $html = '';

    // Convert 'if' attributes to data-if, with class 'if'
    if (isset($attrib['if'])) {
      $attrib['data']['if'] = $attrib['if'];
      $attrib['class'][] = 'if';
      unset($attrib['if']);
    }

    foreach ($attrib as $k => $v) {
      switch ($k) {
      // Single style attributes (multiple="multiple")
      case 'multiple': case 'disabled': case 'readonly': case 'checked':
        $v = $v ? $k : NULL;
        break;
      // Flags that are on when False 
      case 'autocomplete':
        $v = $v ? NULL : 'off';
        break;
      }

      if (is_array($v)) {
        if ($k == 'class') {
          $v = implode(' ', $v);
          if (!$v) continue;
        }elseif (starts_with($k, 'data-')) {
          $v = json_encode($v);
        }else if ($k == 'data') {
          // Append all the values, json encode any non-strings
          foreach ($v as $kk => $vv) {
            if (!is_string($vv)) $vv = json_encode($vv);
            $html .= ' data-'.self::encode($kk).'="'.self::encode($vv).'"';
          }
          continue;
        }else{
          throw new \InvalidArgumentException('html_attributes can only have array value for class or data');
        }
      }else if ($v === NULL) {
        continue;
      }
      $html .= ' '.self::encode($k).'="'.self::encode($v).'"';
    }
    return $html;
  }
}
