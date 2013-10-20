<?php
/**
 * This source file is subject to the "New BSD License".
 *
 * For more information please see http://code.google.com/p/eskymofw/
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 * @link		http://code.google.com/p/eskymofw/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
interface IFormBuilder
{

	const CHECKBOX	= "checkbox";

	const HIDDEN	= "hidden";

	const PASSWORD	= "password";

	const RADIOBOX	= "radiobox";

	const SELECTBOX = "selectbox";

	const TEXTAREA	= "textarea";

	const TEXTINPUT	= "textinput";


	/**
	 * It retursn a built form. This form is built only once and if you call
	 * this method again, you retrieve the same form.
	 *
	 * @return Form
	 */
	function &buildForm();

	/**
	 * It disables a form item
	 *
	 * @param string $attribute
	 */
	function disable($attribute);

	/**
	 * It disables all form items
	 */
	function disableAll();

	/**
	 * It enables a form item
	 *
	 * @param string $attribute
	 */
	function enable($attribute);

	/**
	 * @return IEntity
	 */
	function getEntity();

	/**
	 * It returns a set resource by specified name.
	 *
	 * @param string $name
	 * @return mixed
	 */
	function getResource($name);

	/**
	 * It checks if the form is built.
	 *
	 * @return boolean
	 */
	function isBuilt();

	/**
	 * The simple onSubmit method which persists the entity.
	 *
	 * If you want use this method, you must set the form instance!
	 *
	 * @param Form $form
	 */
	function onSubmit(Form $form);

	/**
	 * It sets a resource for the attribute. If the resource is not an array,
	 * the attribute will be represented as a hidden item in a form.
	 *
	 * @param string $name
	 * @param mixed $resource
	 * @throws InvalidArgumentException if the attribute does not exist,
	 */
	function setResource($attribute, $resource);


}
