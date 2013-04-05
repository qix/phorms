<?php

class FormCheckTest extends \PHPUnit_Framework_TestCase {

  public function testEmpty() {
    $form = new \Phorms\Form(array());

    // Check no data provided
    $this->assertSame($form->check([]), []);

    // Irrelevant data should not create errors
    $this->assertSame($form->check(['irrelevant' => 'data']), []);
  }

  public function testRequired() {
    $form = new \Phorms\Form(array(
      'firstname' => 'Firstname',
      'surname' => ['Surname', 'required' => True],
    ));

    // Surname is required
    $this->assertSame($form->check(array(
      'firstname' => 'John',
    )), array(
      'surname' => 'The surname field is required.'
    ));

    // Empty input is still required
    $this->assertSame($form->check(array(
      'firstname' => 'John', 'surname' => '',
    )), array(
      'surname' => 'The surname field is required.'
    ));

    // Firstname not required
    $this->assertSame($form->check(array(
      'firstname' => '', 'surname' => 'Smith',
    )), array(
    ));
  }

  public function testCustomCheck() {
    $post = array(
      'firstname' => '',
      'surname' => 'Smith',
    );

    $form = new \Phorms\Form(array(
      'firstname' => array('Firstname', 'required' => True),
    ), array(
      'check' => function($data) use ($post) {
        // Check we got given the same post data
        $this->assertSame($data, $post);

        // Return a random error about non-existant surname field
        return array('surname' => 'There is a problem with your surname.');
      }
    ));

    // Assert that we see both errors
    $this->assertSame($form->check($post), array(
      'firstname' => 'The firstname field is required.',
      'surname' => 'There is a problem with your surname.',
    ));
  }

}
