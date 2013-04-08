<?php

class TestRenderInput extends \PHPUnit_Framework_TestCase {

  public function testRenderSimple() {
    $form = new \Phorms\Form(array(
      'firstname' => 'Firstname'
    ), ['action' => False]);

    ob_start();
    $form->render([]);

    $this->assertSame(ob_get_clean(),
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input">'."\n"
    );
  }

  public function testRenderInputWithValue() {
    $form = new \Phorms\Form(array(
      'firstname' => 'Firstname'
    ), ['action' => False]);

    ob_start();
    $form->render(['firstname' => 'George']);

    $this->assertSame(ob_get_clean(),
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input" value="George">'."\n"
    );
  }

  public function testRenderInputWithDefault() {
    $form = new \Phorms\Form(array(
      'firstname' => ['Firstname', 'default' => 'Fred'],
    ), ['action' => False]);

    ob_start();
    $form->render([]);

    $this->assertSame(ob_get_clean(),
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input" value="Fred">'."\n"
    );
  }

  public function testRenderInputWithDefaultAndValue() {
    $form = new \Phorms\Form(array(
      'firstname' => ['Firstname', 'default' => 'Fred'],
    ), ['action' => False]);

    ob_start();
    $form->render(['firstname' => 'George']);

    $this->assertSame(ob_get_clean(),
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input" value="George">'."\n"
    );
  }
}

