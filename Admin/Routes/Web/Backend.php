<?php declare(strict_types=1);

use Modules\InvestmentManagement\Controller\BackendController;
use Modules\InvestmentManagement\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/controlling/investment/list.*$' => [
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
    '^.*/controlling/investment/single.*$' => [
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
    '^.*/controlling/investment/create.*$' => [
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
