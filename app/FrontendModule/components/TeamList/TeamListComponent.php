<?php
class TeamListComponent extends BaseListComponent {

	protected function beforeRender() {
		$this->getTemplate()->teams = $this->getSource()->orderBy('category', 'ASC')->fetchAssoc("category,id_team");
		$ids = $this->getSource()->fetchPairs("id_team", "id_team");
		if(count($ids) == 0) {
			$this->getTemplate()->competitors = array();
		} else {
			$this->getTemplate()->competitors = Interlos::competitors()->findAll()
				->where("[id_team] IN %l", $ids)
				->orderBy("name")
				->fetchAssoc("id_team,id_competitor");
		}
		$this->getTemplate()->categories = Interlos::teams()->getCategories();
	}

}
