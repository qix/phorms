<?php

class TestControlsSelectMultiple extends \PHPUnit_Framework_TestCase {

  public function formProvider() {
    return array(array(
      new \Phorms\Form(array(
        'control' => array('Control', array(
          'A' => 'Option 1',
          'B' => 'Option 2',
          'C' => 'Option 3',
          'D' => 'Option 4',
          'E' => 'Option 5',
        ), 'multiple' => True),
      ), array(
        'action' => False
      )),
    ));
  }

  /**
   * @dataProvider formProvider
   **/
  public function testData($form) {

    // Check should pass with no errors
    $this->assertSame($form->check(['control' => ['A', 'E']]),
      array()
    );

    // Get the expected data out again
    $this->assertSame($form->data(['control' => ['A', 'E']]),
      ['control' => ['A', 'E']]
    );
  }

  /**
   * @dataProvider formProvider
   **/
  public function testInvalidExtra($form) {
    // Check should fail because of missing control
    $this->assertSame($form->check(['control' => ['A', 'E', 'Q']]), array(
      'control' => 'The control field was not valid'
    ));
  }

  /**
   * @dataProvider formProvider
   **/
  public function testEmptyArray($form) {
    // Check should pass (not required)
    $this->assertSame($form->check([]), array());

    // ->data() should return the empty array
    $this->assertSame($form->data([]), array(
      'control' => [],
    ));
  }

  /**
   * @dataProvider formProvider
   **/
  public function testRequired($form) {
    // Set the control to required
    $form->getElement('control')->required = True;

    // Check should fail with no data (or empty data)
    $this->assertSame($form->check([]), array(
      'control' => 'The control field is required.'
    ));
    $this->assertSame($form->check(['control' => []]), array(
      'control' => 'The control field is required.'
    ));

    // Test should pass with good data
    $this->assertSame($form->check(['control' => ['A', 'E']]),
      array()
    );
  }


  /**
   * @dataProvider formProvider
   **/
  public function testRenderSelected($form) {
    ob_start();
    $form->render(['control' => ['A', 'C']]);

    $this->assertSame(ob_get_clean(),
      '<label for="control">Control</label><select name="control[]" id="control" multiple="multiple" size="5">'."\n".
      '<option value="A" selected="selected">Option 1</option>'."\n".
      '<option value="B">Option 2</option>'."\n".
      '<option value="C" selected="selected">Option 3</option>'."\n".
      '<option value="D">Option 4</option>'."\n".
      '<option value="E">Option 5</option>'."\n".
      '</select>'."\n"
    );
  }
}

