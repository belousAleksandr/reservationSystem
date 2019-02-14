<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Manager\ReservationManager;
use App\Manager\ReservationPaymentManager;
use Payum\Core\Payum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/payment")
 * Class PaymentController
 * @package App\Controller
 */
class PaymentController extends AbstractController
{
    /**
     * @Route("/prepare/{id}/{gatewayName}", name="app_reservation_payment_prepare")
     *
     * @param Reservation $reservation
     * @param string $gatewayName
     * @return RedirectResponse
     */
    public function prepareAction(Reservation $reservation, string $gatewayName)
    {
        $reservationManager = $this->get(ReservationPaymentManager::class);

        return $this->redirect($reservationManager->generatePayment($reservation, $gatewayName));
    }

    /**
     * @Route("/done", name="app_reservation_payment_done")
     * @param Request $request
     * @return RedirectResponse
     */
    public function doneAction(Request $request = null)
    {
        $token = $this->get('payum')->getHttpRequestVerifier()->verify($request);

        // You can invalidate the token, so that the URL cannot be requested any more:
         $this->get('payum')->getHttpRequestVerifier()->invalidate($token);

        return $this->redirectToRoute('app_reservation_owner_login');
    }

    public static function getSubscribedServices(): array
    {
        $subscribedServices = parent::getSubscribedServices();
        return array_merge([
            ReservationPaymentManager::class => '?' . ReservationPaymentManager::class,
            'payum' => '?'.Payum::class,
        ], $subscribedServices);
    }
}
