<?php

class FormCheckTest extends \PHPUnit_Framework_TestCase {

  public function testRequired() {
    $form = new \Forms\Form(array(
      'firstname' => 'Firstname',
      'surname' => ['Surname', 'required' => True],
    ));
    $post = array(
      'firstname' => 'John',
      'surname' => '',
    );

    $this->assertSame($form->check($post), array(
      'surname' => 'The surname field is required.'
    ));
  }

}
