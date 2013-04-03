<?php

class FormDataTest extends \PHPUnit_Framework_TestCase {

  public function testEmptyField() {
    $form = new \Forms\Form(array(
      'firstname' => 'Firstname',
      'surname' => 'Surname',
    ));
    $post = array('firstname' => 'George');

    // Assert that surname was dropped
    $this->assertSame($form->data($post), array(
      'firstname' => 'George',
      'surname' => null
    ));
  }

  public function testDropExtra() {
    $form = new \Forms\Form(array(
      'firstname' => 'Firstname'
    ));
    $post = array(
      'firstname' => 'John',
      'surname' => 'Smith',
    );

    // Assert that surname was dropped
    $this->assertSame($form->data($post), array(
      'firstname' => 'John'
    ));
  }

  public function testCustomFill() {
    // Try create a fill function which swaps firstname and surname
    $form = new \Forms\Form(array(
      'firstname' => 'Firstname'
    ), array(
      'fill' => function(&$data) {
        $data['surname'] = $data['firstname'];
        unset($data['firstname']);
      }
    ));

    // Ensure that posting firstname, gives the other surname
    $this->assertSame($form->data(array(
      'firstname' => 'John'
    )), array(
      'surname' => 'John'
    ));
  }

}
