<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Payment;
use App\Entity\Reservation;
use App\Entity\ReservationOwner;
use Payum\Core\Payum;

class ReservationPaymentManager
{
    const RESERVATION_PREFIX = 'reservation_';

    /** @var Payum */
    private $payum;

    /**
     * ReservationManager constructor.
     *
     * @param Payum $payum
     */
    public function __construct(Payum $payum)
    {
        $this->payum = $payum;
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
}