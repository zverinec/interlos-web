<?php
class TaskStatsComponent extends BaseComponent
{

    public function startUp() {
        $this->getTemplate()->tasks = Interlos::tasks()->findAllStats()->fetchAll();
    }

}
