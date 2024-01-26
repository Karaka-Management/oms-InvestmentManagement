<?php declare(strict_types=1);

use Modules\InvestmentManagement\Controller\BackendController;
use Modules\InvestmentManagement\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/finance/investment/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\BackendController:viewInvestmentList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],
    '^.*/finance/investment/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\BackendController:viewInvestmentView',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],
    '^.*/finance/investment/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\BackendController:viewInvestmentCreate',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],
    '^.*/finance/investment/object(\?.*$|$)' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\BackendController:viewInvestmentObjectView',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],

    '^.*/private/investment/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\BackendController:viewInvestmentList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],
    '^.*/private/investment/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\BackendController:viewInvestmentView',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],
];
