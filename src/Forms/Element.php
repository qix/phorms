<?php

namespace Forms;

interface Element {

  /***
   * Returns an array of variables extracted from the provided $post
   **/
  function data($post);

  /***
   * Renders the form with the provided data and prefix
   **/
  function render($data, $prefix='');

  /***
   * Returns an array of errors for the provided data
   **/
  function check($data);

  /***
   * Saves an array of errors for the provided data
   **/
  function errors($errors);

  /***
   * Returns an array of element names
   **/
  function names();

  /***
   * Returns a element given its name
   **/
  function getElement($name);

  /***
   * Searches element by a given selector
   **/
  function queryElements($selector);

  /***
   * Removes a element given its name
   **/
  function removeElement($name);

  /***
   * Replaces one element with another
   **/
  function replaceElement($element, $with);
}

