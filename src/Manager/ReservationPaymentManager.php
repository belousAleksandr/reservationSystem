<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Payment;
use App\Entity\Reservation;
use App\Entity\ReservationOwner;
use App\Event\ReservationPayedEvent;
use App\Events;
use Payum\Core\Payum;
use Payum\Core\Security\TokenInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ReservationPaymentManager
{
    const RESERVATION_PREFIX = 'reservation_';

    /** @var Payum */
    private $payum;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var ReservationManager */
    private $reservationManager;

    /**
     * ReservationPaymentManager constructor.
     *
     * @param Payum $payum
     * @param EventDispatcherInterface $eventDispatcher
     * @param ReservationManager $reservationManager
     */
    public function __construct(Payum $payum, EventDispatcherInterface $eventDispatcher, ReservationManager $reservationManager)
    {
        $this->payum = $payum;
        $this->eventDispatcher = $eventDispatcher;
        $this->reservationManager = $reservationManager;
    }

    /**
     * @param Reservation $reservation
     * @param string $gatewayName
     * @return string
     */
    public function generatePayment(Reservation $reservation, string $gatewayName): string
    {
        $payum = $this->payum;
        /** @var ReservationOwner $reservationOwner */
        $reservationOwner = $reservation->getReservationOwner();
        $storage = $payum->getStorage(Payment::class);
        /** @var Payment $payment */
        $payment = $storage->create();
        $payment->setNumber(self::RESERVATION_PREFIX . $reservation->getId());

        //TODO: Need to fix hardcoded value
        $payment->setCurrencyCode('EUR');
        $payment->setTotalAmount($reservation->getSummaryPrice());
        $payment->setDescription('Reservations seats in a cinema');
        $payment->setClientId($reservationOwner->getId());
        $payment->setClientEmail($reservationOwner->getEmail());

        $reservation->setPayment($payment);

        $storage->update($payment);

        $captureToken = $payum->getTokenFactory()->createCaptureToken(
            $gatewayName,
            $payment,
            'app_reservation_payment_done' // the route to redirect after capture
        );

        return $captureToken->getTargetUrl();
    }

    /**
     * @param TokenInterface $token
     */
    public function done(TokenInterface $token)
    {
        // You can invalidate the token, so that the URL cannot be requested any more:
        $this->payum->getHttpRequestVerifier()->invalidate($token);

        $identity = $token->getDetails();
        /** @var Payment $model */
        $model = $this->payum->getStorage($identity->getClass())->find($identity);
        $reservationId = trim($model->getNumber(), self::RESERVATION_PREFIX);

        $reservation = $this->reservationManager->find($reservationId);

        $this->eventDispatcher->dispatch(Events::RESERVATION_PAYED, new ReservationPayedEvent($reservation));

    }
}