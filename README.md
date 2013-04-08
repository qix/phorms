Phorms
======

Phorms is a simple, yet extensible, library for website forms generated php.
It is designed for systems that have a large number of user input forms, and 
allows definition of complicated forms with a simple syntax.

There is a focus on server side data validation, as well as clear error 
messages for any problems encountered.

Installation with Composer
--------------------------

Declare phorms as a dependency in your projects composer.json file:

```json
{
  "require": {
    "qix/phorms": "dev-master"
  }
}
```

Show me the code
----------------

```php
Phorms\Csrf::setSecret('mysecret');

$form = new Phorms\Form([
  // Set up a fieldset for the fields
  '@fieldset:Your request',

  // Input boxes are the default, and are defined simply:
  'firstname' => 'Firstname',

  // Passing an array as the second option allows extra properties:
  'email' => ['Email', 'type'=>'email', 'required'=>True],

  // Some types are automatically detected, such as a select box:
  'topic' => ['Topic', array(
    'topics/barley.txt' => 'Barley',
    'topics/rice.txt' => 'Rice',
    'topics/wheat.txt' => 'Wheat',
  )],

  // Controls can also be provided as objects (simple challenge response)
  new Phorms\Element_Checkbox(array(
    'name' => 'human',
    'caption' => 'Are you human?',
    'required' => True,
  )),

  // Opening another fieldset will automatically close the previous one
  '@fieldset:Action(s)',
  '@submit:Notify me'
]);

if ($data = $form->data()) {
  // Form was submitted, but check if there were errors
  if ($errors = $form->check($data)) {
    // Inform the user of the errors (or just dump them for now)
    var_dump($errors);
  }else{
    // There were no errors, generate and send the email

    // This following line would usually be dangerous because you allow the
    // user to specify the file path (he could choose any file in the system!)
    // Luckily Phorms will validate that the submitted value was actually one 
    // of the options in the dropdown box.
    $body = file_get_contents($data['topic']);

    // Send out an email, the $data['email'] field was required and validated already
    mail($data['email'], 'Hi '.($data['firstname'] ?: 'there'), $body);

    // Choose to exit here, although a 303 redirect is recommended (Post-Redirect-Get pattern)
    print 'We have sent you an email.';
    exit(0);
  }
}

// Render the form, if there were any errors $data includes the entries
$form->render($data);
```

Still to be implemented
-----------------------

Phorms was recently extracted from the [http://www.snapbill.com](SnapBill Codebase) and 
still requires a heavy amount of work.

It is useable as-is (barely) but needs more development before being useful in other codebases.

Further documentation
---------------------

Documentation or this project is available at: https://phorms.readthedocs.org/en/latest/
