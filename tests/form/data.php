<?php

class FormDataTest extends \PHPUnit_Framework_TestCase {

  public function testDropExtra() {
    $form = new \Forms\Form(array(
      'firstname' => 'Firstname'
    ));
    $post = array(
      'firstname' => 'John',
      'surname' => 'Smith',
    );

    $this->assertSame($form->data($post), array(
      'firstname' => 'John'
    ));
  }

}
