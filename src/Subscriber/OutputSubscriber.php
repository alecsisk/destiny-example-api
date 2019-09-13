<?php

namespace App\Subscriber;

use App\Event\ActionEvent;
use App\Event\MessageEvent;
use App\Event\ProgressEvent;
use App\View\ResultOutput;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OutputSubscriber implements EventSubscriberInterface
{

    /**
     * @var ResultOutput
     */
    private $outputHelper;

    public function __construct(ResultOutput $outputHelper)
    {
        $this->outputHelper = $outputHelper;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            ActionEvent::class => ['onActionEvent', 0],
            MessageEvent::class => ['onMessageEvent', 0],
            ProgressEvent::class => ['onProgressEvent', 0],
        ];
    }

    public function onActionEvent(ActionEvent $event)
    {
        $this->outputHelper->setAction($event->getAction());
    }

    public function onMessageEvent(MessageEvent $event)
    {
        $this->outputHelper->sendMessage($event->getMessage());
    }

    public function onProgressEvent(ProgressEvent $event)
    {
        $this->outputHelper->setMaxProgress($event->getMax());
        $this->outputHelper->setCurrentProgress($event->getCurrent());
    }
}