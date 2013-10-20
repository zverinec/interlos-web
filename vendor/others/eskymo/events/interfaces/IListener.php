<?php
interface IListener extends IEskymoObject
{

	function listen(IEvent $event);

}
