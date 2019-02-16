<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Seans;
use App\Util\SessionClonner;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class SeansAdminController extends CRUDController
{
    /**
     * Copy action.
     *
     * @param Seans $session
     * @throws AccessDeniedException If access is not granted
     * @throws \RuntimeException     If no editable field is defined
     *
     * @return Response
     */
    public function copyAction(Seans $session)
    {

        $this->admin->checkAccess('hall_session_copy', $session);

        try {
            $clonner = $this->get('app.util.session_clonner');
            $newObject = $clonner->copy($session);
//            $this->addFlash(
//                'sonata_flash_success',
//                $this->trans(
//                    'flash_create_success',
//                    ['%name%' => $this->escapeHtml($this->admin->toString($newObject))],
//                    'SonataAdminBundle'
//                )
//            );

            // redirect to edit mode
            return $this->redirect($this->admin->getChild('admin.hall')->generateObjectUrl(
                'admin.seans.edit', $this->admin->getSubject(), ['childChildId' => $newObject->getId()

            ]));
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
        }

        return $this->redirect($this->admin->generateUrl('list'));
    }

}
