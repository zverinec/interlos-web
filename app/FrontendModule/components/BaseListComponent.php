<?php

use Dibi\DataSource;

abstract class BaseListComponent extends BaseComponent {

	/** @var int */
	private $limit = 10;

	/** @persistent */
	public $orderBy;

	/** @persistent */
	public $sorting;

	/** @var DataSource */
	private $source;

	public function getLimit() {
		return $this->limit;
	}

	/**
	 * It sets default sorting of the data source.
	 *
	 * @param string $column
	 * @param string $sorting
	 */
	public function setDefaultSorting($column, $sorting = 'ASC') {
		if (empty($this->orderBy)) {
			$this->sort($column, $sorting);
		}
	}

	public function setLimit($limit) {
		$this->limit = $limit;
	}

	public function setSource(DataSource $source) {
		$this->source = $source;
	}

	// ---- PROTECTED METHODS
	protected function beforeRender() {
		parent::beforeRender();
		if (!empty($this->orderBy)) {
			$this->getSource()->orderBy($this->orderBy, $this->sorting);
		}
	}

	protected function createComponentPaginator($name) {
		$paginator = new VisualPaginatorComponent();
		$paginator->getPaginator()->setItemsPerPage($this->getLimit());
		$paginator->getPaginator()->setItemCount($this->getSource()->count());
		return $paginator;
	}

	/** @return Nette\Utils\Paginator */
	protected function getPaginator() {
		return $this->getComponent("paginator")->getPaginator();
	}

	/** @return DataSource */
	protected function getSource() {
		return $this->source;
	}

	/**
	 * This method sorts a source of the list.
	 *
	 * @param string $column	The column which is used for sorting.
	 * @param string $sorting	Direction of sorting (ASC/DESC). If it is NULL,
	 *				the direction which is oposite to previous direction
	 *				is used.
	 */
	protected function sort($column, $sorting = NULL) {
		$this->orderBy = $column;
		if (empty($sorting)) {
			$this->sorting = ($this->sorting == 'ASC') ? 'DESC' : 'ASC';
		}
		else {
			$this->sorting = $sorting;
		}
	}
}
