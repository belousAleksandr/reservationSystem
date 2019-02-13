<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Seans;
use App\Entity\Seat;
use App\Form\Type\ReservationOwnerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reservationOwner', ReservationOwnerType::class)
            ->add('seats', EntityType::class, [
                'class' => Seat::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('seat')
                        ->leftJoin('seat.row', 'row')
                        ->where('row.seans = :session')
                        ->setParameter('session', $options['session']);
                },
                'choice_label' => function ($seat, $key, $value) {
                    /** @var Seat $seat */
                    return strtoupper($seat->getId());
                },
                'group_by' => function ($seat, $key, $value) {
                    /** @var Seat $seat */
                    $row = $seat->getRow();

                    return $row->getId();
                },
                'choice_attr' => function ($seat, $key, $value) {
                    $attr = [];
                    /** @var Seat $seat */
                    if ($seat->getReservation() !== null) {
                        $attr['disabled'] = true;
                    }

                    return $attr;
                },
                'by_reference' => false,
                'multiple' => true,
                'expanded' => true,
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
