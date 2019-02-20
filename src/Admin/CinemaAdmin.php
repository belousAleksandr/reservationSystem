<?php

declare(strict_types=1);

namespace App\Admin;

use App\Form\Type\ImageType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

final class CinemaAdmin extends AbstractAdmin
{

    /**
     * The Access mapping.
     *
     * @var array [action1 => requiredRole1, action2 => [requiredRole2, requiredRole3]]
     */
    protected $accessMapping = [
        'hall_session_copy' => 'HALL_SESSION_COPY'
    ];

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('hall_session_copy', '{id}/hall-session-copy/{hall_session_id}', [
            '_controller' => 'App\Controller\Admin\HallSessionAdminController:copyAction'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureTabMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, ['edit', 'show'])) {
            return;
        }

        /** @var AdminInterface $admin */
        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');
        $childId = $admin->getRequest()->get('childId');


        if ($this->isGranted('EDIT')) {
            $menu->addChild('Edit cinema', [
                'uri' => $admin->generateUrl('edit', ['id' => $id])
            ]);
        }

        if ($this->isGranted('LIST')) {
            $menu->addChild('Manage Halls', [
                'uri' => $admin->generateUrl('admin.hall.list', ['id' => $id])
            ]);

            $menu->addChild('Reservations', [
                'uri' => $admin->generateUrl('admin.reservation.list', ['id' => $id])
            ]);

            if ($childAdmin instanceof HallAdmin) {

                if ($childAdmin && !in_array($action, ['edit', 'show'])) {
                    return;
                }

                $menu->addChild('Manage sessions', [
                    'uri' => $childAdmin->generateUrl('admin.hall_session.list', ['id' => $childId])
                ]);
            }


        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('name')
            ->add('slug');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('name')
            ->add('slug')
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
            ->add('slug')
            ->add('images', CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'by_reference' => false,
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'label' => 'Fichier(s) :',
                    'prototype' => true
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
