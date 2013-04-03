<?php

class TestRenderSubmit extends \PHPUnit_Framework_TestCase {

  public function testRenderAction() {
    $form = new \Forms\Form(array(
      '@submit:Save changes',
    ));

    ob_start();
    $form->render([]);

    $this->assertSame(ob_get_clean(),
      '<input type="submit" value="Save changes">'."\n"
    );
  }
}
