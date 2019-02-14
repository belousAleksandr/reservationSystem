<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\ReservationOwner;
use App\Entity\Seans;
use App\Entity\Seat;
use App\Form\Type\ReservationOwnerType;
use App\Form\Type\ReservationSeatsType;
use App\Validator\ReservationSeats;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReservationType extends AbstractType
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * ReservationType constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        /** @var Reservation $reservation */
        $reservation = $options['data'];
        if ($user instanceof ReservationOwner) {
            $reservation->setReservationOwner($user);
        } else {
            $builder
                ->add('reservationOwner', ReservationOwnerType::class);
        }

        $builder->add('seats', ReservationSeatsType::class, [
            'session' => $options['session']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'session' => null
        ]);

        $resolver->setAllowedTypes('session', [Seans::class]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'reservation';
    }
}
