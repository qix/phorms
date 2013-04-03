<?php
require __DIR__.'/../vendor/autoload.php';

$form = new Forms\Form(array(
  '@action',
  '@fieldset:Your details',
  'firstname' => 'Firstname',
  'surname' => 'Surname',
  'email' => ['Email', 'type' => 'email', 'required' => True],
  '@fieldset:Action(s)',
  '@submit:Create account',
));

if ($data = $form->data()) {
  if ($errors = $form->check($data)) {
    print '<p>We encountered the following errors:<ul>';
    foreach ($errors as $message) {
      if (is_string($message)) {
        print '<li>'.htmlentities($message).'</li>';
      }
    }
    print '</ul>';
  }else{
    print "Thank you ".htmlentities($data['firstname'])."!";
    exit(0);
  }
}

$form->render($data);
