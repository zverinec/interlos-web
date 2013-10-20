<?php
class XMLFormBuilderFactory
{

    private $forms = array();

    /**
     * @param File $config
     */
    public function  __construct(File $config) {
	if (!$config->exists()) {
	    throw new FileNotFoundException("The config file " . $config->getName() . " does not exist.");
	}
	$forms = simplexml_load_file($config->getPath());
	foreach($forms AS $form) {
	    if (empty($form["name"])) {
		throw new IOException("There is a form in the config file without name.");
	    }
	    $this->forms[(string)$form["name"]] = $form;
	}
    }

    /** @return IFormBuilder */
    public function createBuilder($name, Form $form, IEntity $entity) {
	return new XMLFormBuilder($this,$this->getFormConfig($name), $form, $entity);
    }

    private function getFormConfig($name) {
	if (empty($name)) {
	    throw new NullPointerException("name");
	}
	if (!isset($this->forms[$name])) {
	    throw new InvalidArgumentException("The form with name [$name] does not exist.");
	}
	return $this->forms[$name];
    }

}

