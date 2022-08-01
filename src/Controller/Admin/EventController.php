<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Repository\EventRepository;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilderFactoryInterface;
use Sulu\Component\Rest\ListBuilder\Metadata\FieldDescriptorFactoryInterface;
use Sulu\Component\Rest\ListBuilder\PaginatedRepresentation;
use Sulu\Component\Rest\RestHelperInterface;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Sulu\Component\Rest\AbstractRestController;

/**
 * @RouteResource("event")
 */
class EventController extends AbstractRestController implements ClassResourceInterface, SecuredControllerInterface
{
    private EventRepository $eventRepository;

    public function __construct(
        EventRepository                                      $eventRepository,
        private readonly ViewHandlerInterface                $viewHandler,
        private readonly FieldDescriptorFactoryInterface     $fieldDescriptorFactory,
        private readonly DoctrineListBuilderFactoryInterface $listBuilderFactory,
        private readonly RestHelperInterface                 $restHelper,
        ?TokenStorageInterface                               $tokenStorage = null
    )
    {
        $this->eventRepository = $eventRepository;

        parent::__construct($viewHandler, $tokenStorage);
    }

    public function cgetAction(): Response
    {
        $fieldDescriptors = $this->fieldDescriptorFactory->getFieldDescriptors(Event::RESOURCE_KEY);
        $listBuilder = $this->listBuilderFactory->create(Event::class);
        $this->restHelper->initializeListBuilder($listBuilder, $fieldDescriptors);

        $listRepresentation = new PaginatedRepresentation(
            $listBuilder->execute(),
            Event::RESOURCE_KEY,
            $listBuilder->getCurrentPage(),
            $listBuilder->getLimit(),
            $listBuilder->count()
        );

        return $this->viewHandler->handle(View::create($listRepresentation));
    }

    public function getAction(int $id): Response
    {
        $event = $this->eventRepository->find($id);
        if (!$event) {
            throw new NotFoundHttpException();
        }

        return $this->handleView($this->view($event));
    }

    public function putAction(Request $request, int $id): Response
    {
        $event = $this->eventRepository->find($id);
        if (!$event) {
            throw new NotFoundHttpException();
        }

        $this->mapDataToEntity($request->request->all(), $event);
        $this->eventRepository->save($event);

        return $this->handleView($this->view($event));
    }

    public function postAction(Request $request): Response
    {
        $event = $this->eventRepository->create();

        $this->mapDataToEntity($request->request->all(), $event);
        $this->eventRepository->save($event);

        return $this->handleView($this->view($event, 201));
    }

    public function deleteAction(int $id): Response
    {
        $this->eventRepository->remove($id);

        return $this->handleView($this->view(null, 204));
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function mapDataToEntity(array $data, Event $entity): void
    {
        $entity->setName($data['name']);
        $entity->setStartDate($data['startDate']);
        $entity->setEndDate($data['endDate']);
    }

    public function getSecurityContext(): string
    {
        return Event::SECURITY_CONTEXT;
    }

    public function getLocale(Request $request)
    {
        // TODO: Implement getLocale() method.
    }
}