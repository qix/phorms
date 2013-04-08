<?php

namespace Phorms;

class Renderer extends ClassProperties {
	protected $stack = array();

  function __construct($properties=array()) {
    $this->setProperties($properties);
  }

  function renderContainer(Container $element, $data, $prefix='') {
    // Create a new default renderer for the container
    $element->render($data, $prefix);
  }

  function renderControl($control, $data, $prefix='') {

    // Print out a label for the control
    print '<label'.Html::attributes(array(
        'for' => $control->id,
      )).'>'.Html::encode($control->caption).
      ($control->required ? '<span class="required">*</span>' : '') .
      '</label>';


    if ($control->prefix) {
      print Html::encode($control->prefix);
    }

    if ($control instanceof Element_Input) {

      $attributes = $control->getAttributes($data, $prefix);

      // Add an error class to controls with issues
      if ($control->error) {
        $attributes['class'][] = 'error';
      }

      print '<input'.Html::attributes($attributes).'>';

    }elseif ($control instanceof Element_Select) {
    
      $attributes = $control->getAttributes($data, $prefix);

      print '<select'.Html::attributes($attributes) .'>'."\n";

      $value = $control->getValue($data);
      print Element_Select::renderOptions($control->options, $value, $control->multiple);

      print '</select>';
    }else{
      throw new Exception('Unknown control type for renderControl');
    }

    if ($control->suffix) {
      print Html::encode($control->suffix);
    }

    print "\n";
  }

  function renderSubmit($control, $data, $prefix='') {
    print '<input'.Html::attributes(array(
      'type' => 'submit', 'value' => $control->caption,
    )).'>'."\n";
  }

  function pushStack(Stack $element, $data=array()) {
    // Prevent multiple items of the same type in the stack
    $this->endStack($element->type);

    // Start a new item on the stack
    $fn = "begin$element->type";
    $item = $this->$fn($element, $data);
    $this->stack[] = array($element->type, $item);
  }

  function popStack() {
    list($type, $item) = array_pop($this->stack);
    $fn = "end$type";
    $this->$fn($item);
  }

  function beginFieldset($element, $data) {
    print '<fieldset><legend>'.Html::encode($element->caption).'</legend>'."\n";
  }

  function endFieldset($fieldset) {
    print '</fieldset>'."\n";
  }

  function beginAction($element, $data=array()) {
  }

  function endAction($action) {
  }

  function endStack($until=NULL) {
    if ($until == NULL) {
      while ($this->stack) {
        $this->popStack();
      }
    }else{
      // Count how many items are after the requested to pop in the stack
      $pop = 0;
      foreach ($this->stack as $v) {
        if ($v[0] == $until) $pop = 1;
        else if ($pop) $pop++;
      }

      // Then pop that many off
      while ($pop--) $this->popStack();
    }
  }

  function renderElement(Element $element, $data, $prefix) {
    if ($element instanceof Stack) {
      $this->pushStack($element, $data);
    }elseif ($element instanceof Control) {
      $this->renderControl($element, $data, $prefix);
    }elseif ($element instanceof Container) {
      $this->renderContainer($element, $data, $prefix);
    }elseif ($element instanceof Element_Submit) {
      $this->renderSubmit($element, $data, $prefix);
    }else{
      throw new Exception('Unknown element type to render');
    }
  }

  function render(Container $form, $data, $prefix = '') {
    if ($form->if) {
      $this->pushStack(new Test($prefix.$form->if), $data);
    }

    // Add the forms prefix on
    $prefix .= $form->prefix;

    // Group by the form name if it is set
    if ($form->name) {
      if (isset($data[$form->name])) {
        $data = $data[$form->name];
      }else{
        $data = array();
      }
    }

    // Render the <form> tag if it has an action
    if ($form->action) {
      print '<form'.Html::attributes(array(
        'id' => $form->id,
        'action'=>$form->action,
        'method'=>$form->method,
        'enctype'=>$form->upload ? 'multipart/form-data' : NULL
      )).'>'."\n";

      // Send a _csrf field with the form
      print '<input'.Html::attributes(array(
        'type' => 'hidden',
        'name' => '_csrf',
        'value' => Csrf::generate($form->intent, $form->expire)
      )).'>'."\n";
    }

    // Render each of the elements
    foreach ($form->getElements() as $element) {
      $this->renderElement($element, $data, $prefix);
    }

    // Kill anything remaining on the stack
    $this->endStack(NULL);

    // Close the actual form
    if ($form->action) {
      print '</form>'."\n";
    }
  }
}

