<?php

namespace App\Form;

use App\Entity\Seat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price')
            ->add('status', ChoiceType::class, ['choices' => [
                Seat::STATUS_ENABLED => Seat::STATUS_ENABLED,
                Seat::STATUS_DISABLED => Seat::STATUS_DISABLED
            ]]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Seat::class,
            'label' => false
        ]);
    }
}
