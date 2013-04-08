<?php

class TestRenderAction extends \PHPUnit_Framework_TestCase {

  private function renderForm($form) {
    ob_start();
    $form->render([]);
    $html = ob_get_clean();

    // Return with csrf stripped out
    return preg_replace(
      '/<input type="hidden" name="_csrf" value="[^"]+">/',
      '<input type="hidden" name="_csrf" value="...">',
      $html
    );
  }
  public function testRenderSimple() {
    $form = new \Phorms\Form(array(
      'firstname' => 'Firstname'
    ));

    $this->assertSame($this->renderForm($form),
      '<form action="/phpunit-test" method="POST">'."\n".
      '<input type="hidden" name="_csrf" value="...">'."\n".
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input">'."\n".
      '</form>'."\n"
    );
  }

  public function testGetMethod() {
    $form = new \Phorms\Form(array(
      'firstname' => 'Firstname'
    ), array(
      'method' => 'GET',
    ));

    $this->assertSame($this->renderForm($form),
      '<form action="/phpunit-test" method="GET">'."\n".
      '<input type="hidden" name="_csrf" value="...">'."\n".
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input">'."\n".
      '</form>'."\n"
    );
  }
}
