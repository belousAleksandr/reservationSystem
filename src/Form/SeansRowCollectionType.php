<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeansRowCollectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'seans_rows';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return CollectionType::class;
    }
}