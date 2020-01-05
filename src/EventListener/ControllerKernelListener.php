<?php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ControllerEvent;

class ControllerKernelListener
{
    public function __invoke(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if (method_exists($controller[0], '_init')) {
            $controller[0]->_init();
        }
	}
}