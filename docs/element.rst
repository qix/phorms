Element interface
==================================

.. php:interface:: Element
 
  The root class for any form element. This includes controls (input boxes, select, textarea), stack items (fieldset, actions) as well as containers (forms), 
 
.. php:method:: setDate($year, $month, $day)
 
  Set the date.
 
  :param int $year: The year.
  :param int $month: The month.
  :param int $day: The day.
  :returns: Either false on failure, or the datetime object for method chaining.
 
.. php:method:: setTime($hour, $minute[, $second])
 
  Set the time.
 
  :param int $hour: The hour
  :param int $minute: The minute
  :param int $second: The second
  :returns: Either false on failure, or the datetime object for method chaining.
 
.. php:const:: ATOM
 
  Y-m-d\TH:i:sP

By convention CakePHP renders a view with an inflected version of the action
name.  Returning to our online bakery example, our RecipesController might contain the
``view()``, ``share()``, and ``search()`` actions. The controller would be found
in ``/app/Controller/RecipesController.php`` and contain::

        # /app/Controller/RecipesController.php

        class RecipesController extends AppController {
            public function view($id) {
                //action logic goes here..
            }

            public function share($customerId, $recipeId) {
                //action logic goes here..
            }

            public function search($query) {
                //action logic goes here..
            }
        }
