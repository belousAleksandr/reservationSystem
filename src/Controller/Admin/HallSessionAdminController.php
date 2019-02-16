<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\HallSession;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

final class HallSessionAdminController extends CRUDController
{
    /**
     * Copy action.
     * @ParamConverter("hallSession", options={"mapping": {"hall_session_id": "id"}})
     * @param HallSession $hallSession
     * @throws AccessDeniedException If access is not granted
     * @throws \RuntimeException     If no editable field is defined
     *
     * @return Response
     */
    public function copyAction(HallSession $hallSession)
    {

        $this->admin->checkAccess('hall_session_copy', $hallSession);

        try {
            $clonner = $this->get('app.util.hall_session_clonner');
            $newObject = $clonner->copy($hallSession);
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
                'admin.hall_session.edit', $this->admin->getSubject(), ['childChildId' => $newObject->getId()

            ]));
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
        }

        return $this->redirect($this->admin->generateUrl('list'));
    }

}
