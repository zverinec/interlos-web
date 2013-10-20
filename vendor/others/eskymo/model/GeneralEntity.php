<?php
abstract class GeneralEntity extends EskymoListenableObject implements IEntity {

    /** @var mixed */
    private $id;

    /** @var IEntityFactory */
    private $factory;

    /** @var int */
    private $state;

    // ---- PUBLIC METHODS

    public function  __construct(IEntityFactory &$factory) {
	$this->factory	= $factory;
	$this->state	= IEntity::STATE_NEW;
    }

    public final function addOnPersistListener(IListener $listener) {
	$this->addListener(IEntity::EVENT_PERSISTED, $listener);
    }

    public final function addOnDeleteListener(IListener $listener) {
	$this->addListener(IEntity::EVENT_DELETED, $listener);
    }

    public final function delete() {
	if (!$this->getId() != null) {
	    throw new InvalidStateException("The entity is not ready to be deleted.");
	}
	$this->getFactory()->getDeleter()->delete($this->getId());
	$this->callListeners(IEntity::EVENT_DELETED, new EntityEvent($this));
	$this->setState(IEntity::STATE_DELETED);
    }


    public function getId() {
	if ($this->getState() != IEntity::STATE_NEW && empty($this->id)) {
	    throw new InvalidStateException("The entity has no ID.");
	}
	return $this->id;
    }

    public function getState() {
	return $this->state;
    }

    public final function loadDataFromArray(array $source, $annotation = NULL) {
	if ($this->getState() != IEntity::STATE_NEW) {
	    throw new InvalidStateException("The entity is not in state [NEW]. It can't be loaded from array.");
	}
	foreach ($this->getAttributeNames($annotation) AS $var => $translated) {
	    if (isset($source[$translated])) {
		$this->setAttributeValue($var, $source[$translated]);
	    }
	}
	$this->loadId($source);
	$this->setState(IEntity::STATE_PERSISTED);
	return $this;
    }

    public final function persist() {
	switch($this->getState()) {
	    case IEntity::STATE_NEW:
		$id = $this->getFactory()->getInserter()->insert($this);
		$this->setId($id);
		break;
	    case IEntity::STATE_MODIFIED:
		$this->getFactory()->getUpdater()->update($this);
		break;
	    case IEntity::STATE_PERSISTED:
		break;
	    default:
		throw new InvalidStateException("The entity can not be persisted.");
	}
	$this->setState(IEntity::STATE_PERSISTED);
	$this->callListeners(IEntity::EVENT_PERSISTED, new EntityEvent($this));
	return $this;
    }

    // ---- PROTECTED METHODS

    abstract protected function & getAttributeValue($name);

    /** @return IEntityFactory */
    protected final function getFactory() {
	return $this->factory;
    }

    /**
     * It tries to load ID from the source
     *
     * @param array $source
     */
    protected function loadId(array $source) {
	$key = $this->getIdName();
	if (isset($source[$key])) {
	    $this->setId($source[$key]);
	}
    }

    abstract protected function isAttributeSet($name);

    protected final function setId($id) {
	if ($this->getId() != null) {
	    throw new InvalidStateException("The entity ID has been already set. It can not be set again.");
	}
	$this->id = $id;
    }

    protected final function setState($state) {
	if (empty($state)) {
	    throw new NullPointerException("state");
	}
	$this->state = $state;
    }

    abstract protected function setAttributeValue($name, $value);

}
