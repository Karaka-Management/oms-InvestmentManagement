<?php declare(strict_types=1);

use Modules\InvestmentManagement\Controller\BackendController;
use Modules\InvestmentManagement\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/finance/investment/list.*$' => [
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
    '^.*/finance/investment/single.*$' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\BackendController:viewInvestmentSingle',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],
    '^.*/finance/investment/create.*$' => [
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

    '^.*/private/investment/list.*$' => [
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
    '^.*/private/investment/single.*$' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\BackendController:viewInvestmentSingle',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],
];
