<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Yo\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Tools\URL;
use Thelia\Model\ConfigQuery;
use Yo\Form\ConfigForm;
use Yo\Yo;

/**
 * Class ConfigController
 * @package YoT\Controller
 * @author Manuel Raynaud <manu@thelia.net>
 */
class ConfigController extends BaseAdminController
{

    public function saveAction()
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, ['yo'], AccessManager::UPDATE)) {
            return $response;
        }

        $form = new ConfigForm($this->getRequest());
        $error_message = null;
        $response = null;

        try {
            $configForm = $this->validateForm($form);

            ConfigQuery::write('yo_api_key', $configForm->get('api_key')->getData(), 1, 1);
            ConfigQuery::write('yo_username', $configForm->get('username')->getData(), 1, 1);
            $response = RedirectResponse::create(URL::getInstance()->absoluteUrl('/admin/module/Yo'));

        } catch (FormValidationException $e) {
            $errorMsg = $e->getMessage();
        }

        if (null !== $error_message) {
            $this->setupFormErrorContext(
                'Yo config fail',
                $error_message,
                $form
            );
            $response = $this->render(
                "module-configure",
                [
                    'module_code' => 'Yo'
                ]
            );
        }

        return $response;
    }
}
