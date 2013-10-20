<?php
class EntityEvent extends EskymoObject implements IEvent
{

	/** @var IEntity */
	private $entity;

	public function  __construct(IEntity &$entity) {
		$this->entity = $entity;
	}

	/** @return IEntity */
	public function getEntity() {
		return $this->entity;
	}

}
