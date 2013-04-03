<?php

class FormRenderTest extends \PHPUnit_Framework_TestCase {

  public function testRenderInput() {
    $form = new \Forms\Form(array(
      'firstname' => 'Firstname'
    ));

    ob_start();
    $form->render([]);

    $this->assertSame(ob_get_clean(),
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input">'
    );
  }

}
