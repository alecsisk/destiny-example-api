<?php


namespace App\Subscriber;


use App\Event\MessageEvent;
use App\Event\UserHaveItemEvent;
use App\Service\Result\ResultFormatter;
use App\Service\Result\ResultService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserHaveItemSubscriber implements EventSubscriberInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var ResultService
     */
    private $resultService;
    /**
     * @var ResultFormatter
     */
    private $formatter;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        ResultService $resultService,
        ResultFormatter $formatter
    ) {
        $this->dispatcher = $dispatcher;
        $this->resultService = $resultService;
        $this->formatter = $formatter;
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
            UserHaveItemEvent::class => ['onResultEvent', 0],
        ];
    }

    public function onResultEvent(UserHaveItemEvent $event)
    {
        $result = $this->formatter->format($event->getNickname(), $event->isUserHaveItem());
        $this->resultService->add($result);
        $this->dispatcher->dispatch(new MessageEvent($result));
    }
}