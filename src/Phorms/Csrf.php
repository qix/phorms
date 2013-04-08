<?php

namespace Phorms;

class Csrf {
  private static $secret = null;
  private static $session_id = null;

  static function setSecret($secret) {
    self::$secret = $secret;
  }
  static function setSessionId($session_id) {
    self::$session_id = $session_id;
  }

  private static function hash($session_id, $expire_time, $random, $intent) {
    if (!self::$secret) {
      throw new Exception('No secret found for Phorms Csrf. Please use Phorms::setSecret() before generating any forms.');
    }

    $data = json_encode([
      $session_id, $expire_time, $random, $intent
    ]);

    $result = hash_hmac('sha256', $data, self::$secret);

    // Pack it all together and base64url encode
		$packed = pack('LL',$expire_time, $random).$result;
		return str_replace('+','$',base64_encode($packed));
  }

  /***
   * Returns the session id, or an empty string if none
   **/
  static function getSessionId() {
    if (self::$session_id) return self::$session_id;
    else return session_id();
  }

	static function generate($intent, $expire) {
    // Get the actual expiry time, and a raondom number
    $expire_time = time() + $expire;
    $random = rand();

    // Hash out the data
    return self::hash(self::getSessionId(), $expire_time, $random, $intent);
	}

	static function check($csrf_test, $intent) {
    // Undo base64url encoding, and unpack
		$packed = base64_decode(str_replace('$','+',$csrf_test));
		list(,$expire_time, $random) = unpack('L2',$packed);
		$test_code = substr($packed, -4);

    // Generate the code as we would have before
    $code = self::hash(self::getSessionId(), $expire_time, $random, $intent);

		if ($csrf_test === $code) {
      if (time() < $expire_time) {
        return True;
      }else{
        return NULL;
      }
		}else return False;
	}
}
