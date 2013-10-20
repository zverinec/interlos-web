<?php
/**
 * Description of EskymoObject
 *
 * @author papi
 */
abstract class EskymoObject extends Object implements IEskymoObject
{

	public function getAnnotation($annotation, $attribute = NULL) {
		if (empty($annotation)) {
			throw new NullPointerException("annotation");
		}
		if (empty($attribute)) {
			$reflection = $this->getReflection();
		}
		else {
			$reflection = $this->getReflection()->getProperty($attribute);
		}
		if (!$reflection->hasAnnotation($annotation)) {
			return NULL;
		}
		else {
			return $reflection->getAnnotation($annotation);
		}
	}

	public function getMethods() {
		return get_class_methods($this->getReflection()->getName());
	}

	public function getVars() {
		return array_keys(get_object_vars($this));
	}

	public function equals(EskymoObject &$object) {
		return $this === $object;
	}

}