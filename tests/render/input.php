<?php

class TestRenderInput extends \PHPUnit_Framework_TestCase {

  public function testRenderInput() {
    $form = new \Forms\Form(array(
      'firstname' => 'Firstname'
    ));

    ob_start();
    $form->render([]);

    $this->assertSame(ob_get_clean(),
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input">'."\n"
    );
  }

  public function testRenderInputWithValue() {
    $form = new \Forms\Form(array(
      'firstname' => 'Firstname'
    ));

    ob_start();
    $form->render(['firstname' => 'George']);

    $this->assertSame(ob_get_clean(),
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input" value="George">'."\n"
    );
  }

  public function testRenderInputWithDefault() {
    $form = new \Forms\Form(array(
      'firstname' => ['Firstname', 'default' => 'Fred'],
    ));

    ob_start();
    $form->render([]);

    $this->assertSame(ob_get_clean(),
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input" value="Fred">'."\n"
    );
  }

  public function testRenderInputWithDefaultAndValue() {
    $form = new \Forms\Form(array(
      'firstname' => ['Firstname', 'default' => 'Fred'],
    ));

    ob_start();
    $form->render(['firstname' => 'George']);

    $this->assertSame(ob_get_clean(),
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input" value="George">'."\n"
    );
  }
}

