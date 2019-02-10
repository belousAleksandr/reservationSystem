<?php

declare(strict_types=1);

namespace App\Admin;

use App\Form\SeansRowCollectionType;
use App\Form\SeansRowType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

final class SeansAdmin extends AbstractAdmin
{

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        if ($this->isChild()) {
            return;
        }

        // This is the route configuration as a parent
        $collection->clear();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('name');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('name')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('name')
            ->add('rows', SeansRowCollectionType::class, [
                'entry_type' => SeansRowType::class,
                'entry_options' => [],
                'by_reference'=> false
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('name');
    }
}
