<?php
class XMLFormBuilder extends AFormBuilder
{

    /** @var XMLFormBuilderFactory */
    private $factory;

    private $config;

    /** @var IEntity */
    private $entity;

    // ---- PUBLIC METHODS

    public function  __construct(XMLFormBuilderFactory $factory, $config, Form $form, IEntity $entity) {
	$this->factory	= $factory;
	$this->config	= $config;
	$this->setForm($form);
	$entityName = $config["entity"];
	if (!class_exists($entityName)) {
	    throw new InvalidStateException("The entity [$entityName] does not exist.");
	}
	if (get_class($entity) != $entityName) {
	    throw new InvalidArgumentException("The entity type [" + get_class($entity) + "] does not match with entity type [$entityName] declared in config file.");
	}
	$this->setEntity($entity);
    }

    // ---- PROTECTED METHODS

    protected function &createForm() {
	$form = $this->getForm();
	$config = $this->getConfig();
	// TODO: Cycle detection
	if (isset($config["extends"])) {
	    $form = $this->getFactory()->createBuilder($name, $form)->buildForm();
	}
	foreach($config AS $item) {
	    if (isset($item->attribute)) {
		if (empty($item["name"])) {
		    $form->addGroup();
		}
		else {
		    $form->addGroup((string)$item["name"]);
		}
		foreach($item->attribute AS $attribute) {
		    $this->addAttribute($form, $attribute);
		}
	    }
	    else {
		$this->addAttribute($form, $item);
	    }
	}
	return $form;
    }

    // ---- PRIVATE METHODS

    private function addAttribute(Form $form, $config) {
	    $name = (string)$config["name"];
	    if ($this->isDisabled($name)) {
		continue;
	    }
	    if (isset($config->label)) {
		$label = (string)$config->label;
	    }
	    if ($this->getResource($name) == NULL) {
		if (isset($config->{'without-resource'}->label)) {
		    $label = $config->{'without-resource'}->label;
		}
		$type	    = isset($config->{'without-resource'}->type) ? $config->{'without-resource'}->type : IFormBuilder::TEXTINPUT;
		$resource   = NULL;
	    }
	    else {
		if (isset($config->{'with-resource'}->label)) {
		    $label = $config->{'with-resource'}->label;
		}
		$defaultT   = is_array($this->getResource($name)) ? IFormBuilder::SELECTBOX : IFormBuilder::HIDDEN;
		$type	    = isset($config->{'with-resource'}->type) ? $config->{'with-resource'}->type : $defaultT;
		$resource   = $this->getResource($name);
	    }
	    $this->addItemToForm($form, $name, $label, $type, $resource);
	    if (isset($config->rules)) {
		foreach($config->rules->rule AS $rule) {
		    $rType	    = (string)$rule->type;
		    $message    = (string)$rule->message;
		    $arg	    = (string)$rule->arg;
		    $this->addFormRule($form, $name, $rType, $message, $arg);
		}
	    }
    }

    private function getConfig() {
	return $this->config;
    }

    /** @return XMLBuilderFactory */
    private function getFactory() {
	return $this->factory;
    }

}

