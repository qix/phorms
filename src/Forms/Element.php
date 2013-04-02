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
   * Returns an array of control names
   **/
  function names();

  /***
   * Returns a control given its name
   **/
  function getControl($name);

  /***
   * Searches controls by a given selector
   **/
  function queryControls($selector);

  /***
   * Removes a control given its name
   **/
  function removeControl($name);

  /***
   * Replaces one control with another
   **/
  function replaceControl($control, $with);
}

