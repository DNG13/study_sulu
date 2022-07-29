<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Common\DoctrineListRepresentationFactory;
use App\Entity\Event;
use App\Repository\EventRepository;
use FOS\RestBundle\View\ViewHandlerInterface;
use HandcraftedInTheAlps\RestRoutingBundle\Routing\ClassResourceInterface;
use Sulu\Component\Rest\AbstractRestController;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EventController extends AbstractRestController implements ClassResourceInterface, SecuredControllerInterface
{
    private DoctrineListRepresentationFactory $doctrineListRepresentationFactory;
    private EventRepository $eventRepository;

    public function __construct(
        DoctrineListRepresentationFactory $doctrineListRepresentationFactory,
        EventRepository $eventRepository,
        ViewHandlerInterface $viewHandler,
        ?TokenStorageInterface $tokenStorage = null
    ) {
        $this->doctrineListRepresentationFactory = $doctrineListRepresentationFactory;
        $this->eventRepository = $eventRepository;

        parent::__construct($viewHandler, $tokenStorage);
    }

    public function cgetAction(): Response
    {
        $listRepresentation = $this->doctrineListRepresentationFactory->createDoctrineListRepresentation(
            Event::RESOURCE_KEY
        );

        return $this->handleView($this->view($listRepresentation));
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
}