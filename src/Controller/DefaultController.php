<?php

namespace App\Controller;

use App\Entity\Cinema;
use App\Entity\Reservation;
use App\Entity\HallSession;
use App\Form\ReservationType;
use App\Form\SessionRequestType;
use App\Manager\CinemaManager;
use App\Manager\ReservationManager;
use App\Model\SessionRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class DefaultController
 * @Route("/reserving")
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function indexAction(): Response
    {
       return $this->render('default/index.html.twig', [
           'cinemas' => $this->get(CinemaManager::class)->findBy([])
       ]);
    }

    /**
     * @Route("/{slug}/", name="select_date")
     * @param Cinema $cinema
     * @param Request $request
     * @return Response
     */
    public function selectDateAction(Cinema $cinema, Request $request): Response
    {
        $form = $this->createForm(SessionRequestType::class, null, [
            'method' => 'post',
            'action' => $this->generateUrl('select_date', ['slug' => $cinema->getSlug()])
        ]);
        $form->add('submit', SubmitType::class);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SessionRequest $sessionRequest */
            $sessionRequest = $form->getData();
            return $this->redirectToRoute('select_hall_session', [
                'slug' => $cinema->getSlug(),
                'date' => $sessionRequest->getDate()->format('Y-m-d')]);
        }


        return $this->render('default/select_date.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{slug}/hall-sessions/{date}/", name="select_hall_session")
     *
     * @param Cinema $cinema
     * @param \DateTime $date
     * @return Response
     */
    public function selectHallSessionAction(Cinema $cinema, \DateTime $date): Response
    {
        $hallSessionRepository = $this->getDoctrine()->getRepository(HallSession::class);
        $sessions = $hallSessionRepository->findByCinemaAndDate($cinema, $date);

        return $this->render('default/select_session.html.twig', [
            'sessions' => $sessions,
            'cinema' => $cinema]);
    }

    /**
     * @Route("/{slug}/session/{id}/", name="selectSeats")
     * @ParamConverter("cinema", options={"mapping": {"slug": "slug"}})
     *
     * @param Cinema $cinema
     * @param HallSession $hallSession
     * @param Request $request
     *
     * @return Response
     */
    public function selectSeatsAction(Cinema $cinema, HallSession $hallSession, Request $request): Response
    {
        $reservation = new Reservation();
        $reservation
            ->setCinema($cinema)
            ->setSession($hallSession);

        $form = $this->createForm(ReservationType::class, $reservation, [
            'action' => $this->generateUrl('selectSeats', [
                'slug' => $cinema->getSlug(),
                'id' => $hallSession->getId()
            ]),
            'hall_session' => $hallSession]);

        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationManager = $this->get(ReservationManager::class);
            $reservationManager->create($reservation);

            return $this->redirectToRoute('app_reservation_payment_prepare', [
                'id' => $reservation->getId(),
                'gatewayName' => 'offline'
            ]);
        }

        return $this->render('default/select_seats.html.twig', [
            'form' => $form->createView(),
            'hallSession' => $hallSession
        ]);
    }

    public static function getSubscribedServices(): array
    {
        $subscribedServices = parent::getSubscribedServices();
        return array_merge([
            ReservationManager::class => '?'.ReservationManager::class,
            CinemaManager::class => '?'.CinemaManager::class,
        ], $subscribedServices);
    }
}
