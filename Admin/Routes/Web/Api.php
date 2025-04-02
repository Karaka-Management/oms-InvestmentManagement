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

    '^.*/finance/investment(\?.*$|$)' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiController:apiInvestmentCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiController:apiInvestmentUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],

    '^.*/finance/investment/option(\?.*$|$)' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiController:apiInvestmentOptionCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiController:apiInvestmentOptionUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],

    '^.*/finance/investment/option/file(\?.*|$)$' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiController:apiMediaAddToInvestmentObject',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
    ],

    '^.*/finance/investment/option/note(\?.*|$)$' => [
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiController:apiNoteCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiController:apiNoteUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::INVESTMENT,
            ],
        ],
        [
            'dest'       => '\Modules\InvestmentManagement\Controller\ApiController:apiNoteDelete',
            'verb'       => RouteVerb::DELETE,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::DELETE,
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
