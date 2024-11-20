<?php
class ClockComponent extends BaseComponent
{

    public function render()
    {
        $this->template->microtime = microtime(true);
        parent::render();
    }


}
