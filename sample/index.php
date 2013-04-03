<?php
require __DIR__.'/../vendor/autoload.php';

$form = new Forms\Form(array(
  '@action',
  '@fieldset:Your details',
  'firstname' => 'Firstname',
  'surname' => 'Surname',
  'email' => ['Email', 'type' => 'email', 'required' => True],
  'color' => ['Color', ['green' => 'Green (good)', 'red' => 'Red (bad)']],
  '@fieldset:Action(s)',
  '@submit:Create account',
));

print '<html><head><link rel="stylesheet" type="text/css" href="style.css"></head><body>';

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
    print '<pre>'.htmlentities(var_export($data, True)).'</pre>';
    exit(0);
  }
}

$form->render($data);

print '</body></html>';
