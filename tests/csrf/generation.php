<?php

class TestCsrfGeneration extends \PHPUnit_Framework_TestCase {

  public function testExpiry() {
    $code = Phorms\Csrf::generate('/', 60);
    $this->assertTrue(Phorms\Csrf::check($code, '/'));

    $code = Phorms\Csrf::generate('/', -1);
    $this->assertNull(Phorms\Csrf::check($code, '/'));
  }

  public function testIntents() {
    $code = Phorms\Csrf::generate('/okay', 60);

    $this->assertFalse(Phorms\Csrf::check($code, '/fail'));
    $this->assertTrue(Phorms\Csrf::check($code, '/okay'));
  }
}

