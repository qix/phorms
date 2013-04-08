<?php

class TestRenderFieldset extends \PHPUnit_Framework_TestCase {

  public function testRenderSimple() {
    $form = new \Phorms\Form(array(
      '@fieldset:Details',
      'firstname' => 'Firstname'
    ), ['action' => False]);

    ob_start();
    $form->render([]);

    $this->assertSame(ob_get_clean(),
      '<fieldset><legend>Details</legend>'."\n".
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input">'."\n".
      '</fieldset>'."\n"
    );
  }

  public function testRenderTwoFieldsets() {
    $form = new \Phorms\Form(array(
      '@fieldset:Details',
      'firstname' => 'Firstname',
      '@fieldset:Second',
      'surname' => 'Surname',
    ), ['action' => False]);

    ob_start();
    $form->render([]);

    $this->assertSame(ob_get_clean(),
      '<fieldset><legend>Details</legend>'."\n".
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input">'."\n".
      '</fieldset>'."\n".
      '<fieldset><legend>Second</legend>'."\n".
      '<label for="surname">Surname</label><input name="surname" id="surname" type="input">'."\n".
      '</fieldset>'."\n"
    );
  }

  public function testRenderFieldsetInFieldset() {
    $form = new \Phorms\Form(array(
      '@fieldset:Details',
      'firstname' => 'Firstname',
      new \Phorms\Form(array(
        '@fieldset:Inside',
        'surname' => 'Surname',
      ), ['action' => False]),
    ), ['action' => False]);

    ob_start();
    $form->render([]);

    $this->assertSame(ob_get_clean(),
      '<fieldset><legend>Details</legend>'."\n".
      '<label for="firstname">Firstname</label><input name="firstname" id="firstname" type="input">'."\n".
      '<fieldset><legend>Inside</legend>'."\n".
      '<label for="surname">Surname</label><input name="surname" id="surname" type="input">'."\n".
      '</fieldset>'."\n".
      '</fieldset>'."\n"
    );
  }
}
