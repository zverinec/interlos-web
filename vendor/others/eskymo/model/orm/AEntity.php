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
class AEntity extends GeneralEntity implements IEntity {

    /* STATIC ATTRIBUTES */

    /**
     * This array contains translated attributes for each subclass
     * and foreach annotation name
     *
     * @var array
     */
    private static $translatedAttributes = array();

    /**
     * This array contains translated ID name for each subclass
     *
     * @var array
     */
    private static $translatedIds = array();

	/* COMMON PRIVATE ATTRIBUTES */

    /** @var array */
    private $modified = array();


	/* PUBLIC METHODS */

    public function  __construct(IEntityFactory &$factory) {
	parent::__construct($factory);
	$this->addOnPersistListener(new CallbackListener(array($this, "clearModifiedColumns")));
    }

    public function & __get($name) {
	if (in_array($name, $this->getVars())) {
	    return $this->getAttributeValue($name);
	}
	else {
	    return parent::__get($name);
	}
    }

    public function __set($name, $value) {
	if (in_array($name, $this->getVars())) {
	    $this->setAttributeValue($name, $value);
	}
	else {
	    parent::__set($name, $value);
	}
    }

    /**
     * It returns translated attribute names by the specified annotation
     *
     * @param string $annotation
     * @return array
     * @throws NullPointerException if the $annotation is empty
     */
    public function getAttributeNames($annotation = NULL) {
	if (!isset(self::$translatedAttributes[$this->getReflection()->getName()])) {
	    self::$translatedAttributes[$this->getReflection()->getName()] = array();
	}
	if (!isset(self::$translatedAttributes[$this->getReflection()->getName()][$annotation])) {
	    $translated = array();
	    foreach($this->getVars() AS $var) {
		if (!empty($annotation)) {
		    $toSkip			= $this->getAnnotation("Skip", $var);
		    $translatedVar	= $this->getAnnotation("Translate", $var);
		    $description	= $this->getAnnotation($annotation, $var);
		}
		else {
		    $toSkip			= NULL;
		    $translatedVar	= NULL;
		    $description	= NULL;
		}
		// The variables which has 'Skip' annotation will be skipped
		if (!empty($toSkip)) {
		    if ((!is_array($toSkip) && $toSkip == $annotation) || (is_array($toSkip) && in_array($annotation, $toSkip))) {
			continue;
		    }
		}
		// Check if there is an annotation to change the column name
		// (Defaultly the column name is the same as variable name)
		if (!empty($description) && isset($description->translate)) {
		    $translatedVar = $description->translate;
		}
		else if(empty($translatedVar)) {
			$translatedVar = $var;
		    }

		$translated[$var] = $translatedVar;
	    }
	    self::$translatedAttributes[$this->getReflection()->getName()][$annotation] = $translated;
	}
	return self::$translatedAttributes[$this->getReflection()->getName()][$annotation];
    }

    public function getAttributeType($attribute) {
	if (empty($attribute)) {
	    throw new NullPointerException("attribute");
	}
	if (!in_array($attribute, $this->getAttributeNames())) {
	    throw new InvalidArgumentException("The attribute [$attribute] does not exist.");
	}
	$type = $this->getAnnotation("Type", $attribute);
	if (!empty($type) && isset($type->name) && $type->name == "enum" && empty($type->values)) {
	    throw new InvalidStateException("The type [enum] is set, but the values are not set.");
	}
	if (!empty($type) && isset($type->values) && !is_array($type->values)) {
	    $type->values = split(":", $type->values);
	}
	return $type;
    }

    /**
     * It returns data from the entity
     *
     * @param string $annotation
     * @param string $modifier Only IEntity::DATA_ALL is implemented
     * @return array
     */
    public function getData($annotation = NULL, $modifier = self::DATA_ALL) {
	if ($modifier != IEntity::DATA_ALL) {
	    throw new NotSupportedException("Only the modifier [ALL] is supported.");
	}
	$result = array();
	if (!empty($annotation)) {
	    foreach ($this->getAttributeNames($annotation) AS $var => $translated) {
		if (!isset($this->$var)) {
		    continue;
		}
		$result[$translated] = $this->$var;
	    }
	}
	else {
	    foreach($this->getVars() AS $var) {
		if (!isset($this->$var)) {
		    continue;
		}
		$result[$var] = $this->$var;
	    }
	}
	return $result;
    }

    /**
     * It returns the key name which is used to load ID
     *
     * @return string
     * @throws InvalidStateException if the class has no annotation
     * which translates the key
     */
    public function getIdName() {
	if (!isset(self::$translatedIds[$this->getReflection()->getName()])) {
	    $description = $this->getAnnotation("Id");
	    if (empty($description)) {
		throw new InvalidStateException("The annotation [Id] has to be set.");
	    }
	    if (!isset($description->translate)) {
		throw new InvalidStateException("The annotation [Id] has to contain parameter [translate]");
	    }
	    self::$translatedIds[$this->getReflection()->getName()] = $description->translate;
	}
	return self::$translatedIds[$this->getReflection()->getName()];
    }

    public function clearModifiedColumns() {
	$this->modified = array();
    }

    /* PROTECTED METHODS */

    protected function & getAttributeValue($name) {
	return $this->$name;
    }

    protected function isAttributeSet($name) {
	return isset($this->$name);
    }

    protected function setAttributeValue($column, $value) {
	if ($this->$column != $value) {
	    $this->$column = $value;
	    $this->addModifiedColumn($column);
	}
    }

    /* PRIVATE METHODS */

    private function addModifiedColumn($column) {
	$this->modified[$column] = TRUE;
	if ($this->getState() == IEntity::STATE_PERSISTED) {
	    $this->setState(IEntity::STATE_MODIFIED);
	}
    }
}
