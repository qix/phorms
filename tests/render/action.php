<?php

class TestRenderAction extends \PHPUnit_Framework_TestCase {

  public function testRenderAction() {
    $form = new \Phorms\Form(array(
      '@action:/',
      'firstname' => 'Firstname'
    ));

    ob_start();
    $form->render([]);

    $this->assertSame(ob_get_clean(),
      '<form action="/" method="POST">'."\n".
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input">'."\n".
      '</form>'."\n"
    );
  }

  public function testGetMethod() {
    $form = new \Phorms\Form(array(
      '@action:/' => ['method' => 'GET'],
      'firstname' => 'Firstname'
    ));

    ob_start();
    $form->render([]);

    $this->assertSame(ob_get_clean(),
      '<form action="/" method="GET">'."\n".
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input">'."\n".
      '</form>'."\n"
    );
  }
}
