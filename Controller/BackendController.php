<?php
/**
 * Orange Management
 *
 * PHP Version 8.1
 *
 * @package   Modules\InvestmentManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\InvestmentManagement\Controller;

use phpOMS\Contract\RenderableInterface;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

/**
 * Investmenting controller class.
 *
 * @package Modules\InvestmentManagement
 * @license OMS License 2.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class BackendController extends Controller
{
    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewInvestmentDashboard(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/InvestmentManagement/Theme/Backend/investment-dashboard');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1004601001, $request, $response);

        return $view;
    }
}
