<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\InvestmentManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\InvestmentManagement\Controller;

use Modules\InvestmentManagement\Models\InvestmentMapper;
use Modules\InvestmentManagement\Models\InvestmentTypeMapper;
use Modules\Organization\Models\UnitMapper;
use phpOMS\Contract\RenderableInterface;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

/**
 * Investment controller class.
 *
 * @package Modules\InvestmentManagement
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class BackendController extends Controller
{
    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewInvestmentList(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/InvestmentManagement/Theme/Backend/investment-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007101001, $request, $response);

        $list = InvestmentMapper::getAll()
            ->with('createdBy')
            ->sort('id', 'DESC')
            ->execute();

        $view->data['investments'] = $list;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewInvestmentView(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/InvestmentManagement/Theme/Backend/investment-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007101001, $request, $response);

        $investment = InvestmentMapper::get()
            ->with('notes')
            ->with('files')
            ->with('createdBy')
            ->with('options')
            ->with('options/supplier')
            ->with('options/supplier/account')
            ->with('options/item')
            ->with('options/files')
            ->with('options/notes')
            ->with('options/amountGroups')
            ->with('options/amountGroups/type')
            ->with('options/amountGroups/amounts')
            ->with('options/attributes')
            ->with('options/attributes/type')
            ->with('options/attributes/type/l11n')
            ->with('options/attributes/value')
            ->where('id', (int) $request->getData('id'))
            //->where('options/attributes/type/l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['investment'] = $investment;

        $view->data['attributeView']                               = new \Modules\Attribute\Theme\Backend\Components\AttributeView($this->app->l11nManager, $request, $response);
        $view->data['attributeView']->data['default_localization'] = $this->app->l11nServer;

        $investmentTypes = InvestmentTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['types'] = $investmentTypes;

        $units = UnitMapper::getAll()
            ->execute();

        $view->data['units'] = $units;

        $view->data['media-upload'] = new \Modules\Media\Theme\Backend\Components\Upload\BaseView($this->app->l11nManager, $request, $response);
        $view->data['note']         = new \Modules\Editor\Theme\Backend\Components\Note\BaseView($this->app->l11nManager, $request, $response);

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewInvestmentCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/InvestmentManagement/Theme/Backend/investment-create');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007101001, $request, $response);

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewInvestmentOptionCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/InvestmentManagement/Theme/Backend/investment-option-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007101001, $request, $response);

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewInvestmentOptionView(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/InvestmentManagement/Theme/Backend/investment-option-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007101001, $request, $response);

        return $view;
    }
}
