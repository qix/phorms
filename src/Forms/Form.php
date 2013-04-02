<?php
namespace Forms;

class Form extends Container {

  function rendererFactory() {
    return new Renderer();
  }

  function renderWith($renderer, $data, $prefix='') {
    $renderer->render($this, $data, $prefix);
  }

	function render($data, $prefix='') {
    $renderer = $this->rendererFactory();
		$this->renderWith($renderer, $data, $prefix);
	}

}
