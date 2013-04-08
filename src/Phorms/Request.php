<?php

namespace Phorms;

class Request {
  private static $request_uri = null;

  static function setRequestUri($request_uri) {
    self::$request_uri = $request_uri;
  }

  static function getRequestUri() {
    if (self::$request_uri !== null) {
      return self::$request_uri;
    }elseif (isset($_SERVER['REQUEST_URI'])) {
      return $_SERVER['REQUEST_URI'];
    }else{
      return null;
    }
  }
}
