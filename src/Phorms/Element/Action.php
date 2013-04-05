<?php

namespace Phorms;

class Element_Action extends Stack {
  protected $_url = null;
  protected $_method = 'POST';
  protected $_upload = False;

  function setProperties($properties) {
    //  Translate caption => url
    if (isset($properties['caption'])) {
      if (isset($properties['url'])) {
        throw new Exception('Cannot provide both caption and url for an action block');
      }

      $properties['url'] = $properties['caption'];
      unset($properties['caption']);
    }

    parent::setProperties($properties);
  }
}
