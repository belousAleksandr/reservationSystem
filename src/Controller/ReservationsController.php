<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Reservation;
use \Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ReservationsController
 * @Route("/reservations", name="reservations")
 * @package App\Controller
 */
class ReservationsController extends Controller
{
    /**
     * @Route("/", name="index")
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();

        // TODO Seems this logic need to extract into a separate class
        $reservationsRepository = $this->getDoctrine()->getRepository(Reservation::class);
        $reservationsQuery = $reservationsRepository->createQueryBuilder('p')
            ->where('p.reservationOwner != :owner')
            ->setParameter('owner', $user)
            ->getQuery();

        /* @var $paginator Paginator */
        $paginator  = $this->get('knp_paginator');
        // Paginate the results of the query
        $reservations = $paginator->paginate(
        // Doctrine Query, not results
            $reservationsQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );

        return $this->wrappedRender('reservations/index.html.twig', [
            'reservations' => $reservations
        ]);
    }

    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    protected function wrappedRender($view, array $parameters = [], Response $response = null): Response
    {

        $parameters['base_template'] = $parameters['base_template'] ?? 'reservations/base.html.twig';
        return parent::render($view, $parameters, $response);
    }
}