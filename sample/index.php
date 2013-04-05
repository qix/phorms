<?php
require __DIR__.'/vendor/autoload.php';

$form = new Phorms\Form(array(
  '@action',
  '@fieldset:Your details',
  'firstname' => 'Firstname',
  'surname' => 'Surname',
  'email' => ['Email', 'type' => 'email', 'required' => True],
  'color' => ['Color', ['green' => 'Green (good)', 'red' => 'Red (bad)']],
  'movie' => ['Movies', [116 => 'Braveheart', 327 => 'The Rock', 955 => 'Alien II'], 'multiple' => True],
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

    // You could exit at this point, or just re-render the form with the data
    print '<hr/>';
  }
}

$form->render($data);

print '</body></html>';
