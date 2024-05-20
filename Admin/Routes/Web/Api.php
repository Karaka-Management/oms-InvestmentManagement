<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\InvestmentManagement\Controller\ApiController;
use Modules\InvestmentManagement\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/finance/investment/find(\?.*$|$)' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiController:apiInvestmentFind',
            'verb'       => RouteVerb::GET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],
    '^.*/finance/investment/attribute(\?.*|$)$' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiAttributeController:apiInvestmentAttributeCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiAttributeController:apiInvestmentAttributeUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],
    '^.*/finance/investment/attribute/type(\?.*|$)$' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiAttributeController:apiInvestmentAttributeTypeCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiAttributeController:apiInvestmentAttributeTypeUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],
    '^.*/finance/investment/attribute/type/l11n(\?.*|$)$' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiAttributeController:apiInvestmentAttributeTypeL11nCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiAttributeController:apiInvestmentAttributeTypeL11nUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],
    '^.*/finance/investment/attribute/value(\?.*|$)$' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiAttributeController:apiInvestmentAttributeValueCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiAttributeController:apiInvestmentAttributeValueUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],
    '^.*/finance/investment/attribute/value(\?.*|$)$' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiAttributeController:apiInvestmentAttributeValueL11nCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiAttributeController:apiInvestmentAttributeValueL11nUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],
];
