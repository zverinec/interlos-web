<?php
interface IEskymoObject
{
	/**
	 * It returns the class annotation value. If the attribute is set,
	 * the attribute annotation value will be returned.
	 *
	 * @param string $annotation
	 * @param string $attribute
	 */
	function getAnnotation($annotation, $attribute = NULL);

	/**
	 * It returns object methods
	 *
	 * @return array Method names
	 */
	public function getMethods();

	/**
	 * It returns object variables
	 *
	 * @return array Variable names
	 */
	public function getVars();

	/**
	 * It checks if this instance is equals to another one.
	 *
	 * @param EskymoObject $object
	 * @return bool
	 * @throws NullPointerException if the $object is empty
	 */
	public function equals(EskymoObject &$object);
}
