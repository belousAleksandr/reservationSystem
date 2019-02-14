<?php

namespace App\Form\Type;

use App\Entity\Seans;
use App\Entity\Seat;
use App\Repository\SeatRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationSeatsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setNormalizer('query_builder', function (OptionsResolver $optionsResolver) {
            /** @var EntityManager $em */
            $em = $optionsResolver->offsetGet('em');
            /** @var SeatRepository $seatsRepository */
            $seatsRepository = $em->getRepository($optionsResolver->offsetGet('class'));

            return $seatsRepository->createQueryBuilder('seat')
                ->leftJoin('seat.row', 'row')
                ->where('row.seans = :session')
                ->setParameter('session', $optionsResolver->offsetGet('session'));
        });

        $resolver->setDefault('class', Seat::class);
        $resolver->setDefault('multiple', true);
        $resolver->setDefault('expanded', true);
        $resolver->setDefault('by_reference', false);
        $resolver->setDefault('choice_label', function ($seat, $key, $value) {
            /** @var Seat $seat */
            return strtoupper($seat->getId());
        });

        $resolver->setDefault('group_by', function ($seat, $key, $value) {
            /** @var Seat $seat */
            $row = $seat->getRow();

            return $row->getId();
        });

        $resolver->setDefault('choice_attr', function ($seat, $key, $value) {
            $attr = [];
            /** @var Seat $seat */
            if ($seat->getReservation() !== null) {
                $attr['disabled'] = true;
            }

            return $attr;
        });

        $resolver->setRequired('session');
        $resolver->setAllowedTypes('session', Seans::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }
}
