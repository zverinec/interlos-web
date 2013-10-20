<?php

class SimpleFormBuilder extends AFormBuilder implements IFormBuilder {

    /**
     * The builder is set by the entity and the form. The implementation of AppForm
     * is recommended
     *
     * @param IEntity $entity
     * @param Form $form
     */
    public function  __construct(IEntity $entity, Form $form) {
	$this->setEntity($entity);
	$this->setForm($form);
    }

    /* PRIVATE METHODS */

    private function addRules($attribute, FormControl $control) {
	$annotation = $this->getEntity()->getAnnotation("Rule",$attribute);
	// If the rule annotation is not set. Do nothing.
	if ($annotation == NULL) {
	    return $control;
	}
	// Hack	- If there are more than one rule, $annotation is an array.
	//		  If there is only one rule, it is converted to the array.
	if (!is_array($annotation)) {
	    $annotation = array($annotation);
	}
	// Add each rule.
	foreach ($annotation AS $rule) {
	    $control->addRule(
		constant("Form::" . String::upper($rule->type)),
		$attribute . "_" . $rule->type . "_msg",
		isset($rule->arg) ? $rule->arg : NULL
	    );
	}
    }

    private function addFormItem(Form &$form, $attribute) {
	// Get the Form annotation which specified the form element type
	$annotation = $this->getEntity()->getAnnotation("Form", $attribute);
	// Translate the attribute by the Form annotation
	$translated = $this->getEntity()->getAttributeNames("Form");
	$translatedAttribute = $translated[$attribute];
	$label = ucfirst(strtr($translatedAttribute, "_", " "));
	// If the attribute type is enum
	$type = $this->getEntity()->getAttributeType($attribute);
	if (!empty($type) && isset($type->name) && $type->name == "enum") {
	    $resource = array();
	    foreach($type->values AS $value) {
		$resource[$value] = $value;
	    }
	    $this->setResource($translatedAttribute, $resource);
	}
	// If the resource is set
	if ($this->getResource($translatedAttribute) != NULL) {
	// If the resource is not an array - add hidden input
	    if (!is_array($this->getResource($translatedAttribute))) {
		$form->addHidden($translatedAttribute);
	    }
	    // If the annotation does not specify the form element type, add a selectbox
	    elseif(empty($annotation) || !isset($annotation->withResource) || $annotation->withResource == "selectbox") {
		$form->addSelect($translatedAttribute, $label, $this->getResource($translatedAttribute));
	    }
	    // Otherwise add the specified element
	    else {
		switch($annotation->withResource) {
		    case "radiobox":
			$form->addRadioList($translatedAttribute, $label, $this->getResource($translatedAttribute));
			break;
		    case "checkbox":
			break;
		    case "password":
			$form->addPassword($translatedAttribute, $label);
			break;
		    default:
			throw new NotSupportedException("The form element type [".$annotation->withResource."] is not supported");
		}
	    }
	}
	// The resource is not set
	else {
	// If the annotation does not specify the form element type, add a text input
	    if (empty($annotation) || !isset($annotation->withoutResource) || $annotation->withoutResource == "text") {
		$form->addText($translatedAttribute, $label);
	    }
	    // Otherwise add specified element.
	    else {
		switch($annotation->withoutResource) {
		    case "textarea":
			$form->addTextArea($translatedAttribute, $label);
			break;
		    case "password":
			$form->addPassword($translatedAttribute, $label);
			break;
		    default:
			throw new NotSupportedException("The form element type [".$annotation->withoutResource."] is not supported");
			break;
		}
	    }
	}
	// Add validation rules
	$this->addRules($attribute, $form->getComponent($translatedAttribute));
    }

    protected function &createForm() {
	$form = $this->getForm();
	// Foreach attribute add a form element
	foreach($this->getEntity()->getAttributeNames("Form") AS $attribute => $translatedAttribute) {
	    if (!$this->isDisabled($translatedAttribute)) {
		$this->addFormItem($form, $attribute);
	    }
	}
	return $form;
    }

}
