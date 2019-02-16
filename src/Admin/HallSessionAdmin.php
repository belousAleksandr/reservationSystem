<?php

declare(strict_types=1);

namespace App\Admin;

use App\Form\HallSessionRowCollectionType;
use App\Form\HallSessionRowType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DateTimePickerType;

final class HallSessionAdmin extends AbstractAdmin
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
                    'copy' => ['template' => 'admin\SessionAdmin\list__action_copy.html.twig'],
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
            ->add('datetime', DateTimePickerType::class, [
                'dp_side_by_side'       => true,
                'dp_use_current'        => false,
                'dp_use_seconds'        => false,
                'dp_collapse'           => true,
                'dp_calendar_weeks'     => false,
                'dp_view_mode'          => 'days',
                'dp_min_view_mode'      => 'days',
                'dp_min_date' => (new \DateTime())->format('Y-m-d'),
            ])
            ->add('rows', HallSessionRowCollectionType::class, [
                'entry_type' => HallSessionRowType::class,
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
