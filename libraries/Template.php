<?php

class Template
{
	protected $template;
	protected $vars = array();

	public function __construct($template)
	{
		$this->template = $template;
	}

	public function __get($key){
		return $this->vars[$key];
	}





	public function __set($key, $value){
		 $this->vars[$key] = $value;
	}


	public function __toString(){

		extract($this->vars); 
		chdir(dirname($this->template));

		ob_start();
		include '../templates/'. basename($this->template);
		
		return ob_get_clean();
	}
}