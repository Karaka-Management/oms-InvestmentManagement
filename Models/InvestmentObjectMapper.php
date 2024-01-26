<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\InvestmentManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\InvestmentManagement\Models;

use Modules\Editor\Models\EditorDocMapper;
use Modules\InvestmentManagement\Models\Attribute\InvestmentObjectAttributeMapper;
use Modules\ItemManagement\Models\ItemMapper;
use Modules\Media\Models\MediaMapper;
use Modules\SupplierManagement\Models\SupplierMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\InvestmentManagement\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of InvestmentObject
 * @extends DataMapperFactory<T>
 */
final class InvestmentObjectMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'investmgmt_option_id'           => ['name' => 'investmgmt_option_id',         'type' => 'int',      'internal' => 'id'],
        'investmgmt_option_name'         => ['name' => 'investmgmt_option_name',      'type' => 'string',   'internal' => 'name'],
        'investmgmt_option_description'  => ['name' => 'investmgmt_option_description',       'type' => 'string',   'internal' => 'description'],
        'investmgmt_option_link'         => ['name' => 'investmgmt_option_link',       'type' => 'string',   'internal' => 'link'],
        'investmgmt_option_supplier'     => ['name' => 'investmgmt_option_supplier',      'type' => 'int',   'internal' => 'supplier'],
        'investmgmt_option_supplier_alt' => ['name' => 'investmgmt_option_supplier_alt',       'type' => 'string',   'internal' => 'supplierName'],
        'investmgmt_option_item'         => ['name' => 'investmgmt_option_item',      'type' => 'int',   'internal' => 'item'],
        'investmgmt_option_approved'     => ['name' => 'investmgmt_option_approved',      'type' => 'bool',   'internal' => 'approved'],
        'investmgmt_option_investment'   => ['name' => 'investmgmt_option_investment',      'type' => 'int',   'internal' => 'investment'],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'supplier' => [
            'mapper'   => SupplierMapper::class,
            'external' => 'investmgmt_option_supplier',
        ],
        'item' => [
            'mapper'   => ItemMapper::class,
            'external' => 'investmgmt_option_item',
        ],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'files' => [
            'mapper'   => MediaMapper::class,            /* mapper of the related object */
            'table'    => 'investmgmt_option_media',         /* table of the related object, null if no relation table is used (many->1) */
            'external' => 'investmgmt_option_media_media',
            'self'     => 'investmgmt_option_media_option',
        ],
        'notes' => [
            'mapper'   => EditorDocMapper::class,       /* mapper of the related object */
            'table'    => 'investmgmt_option_note',         /* table of the related object, null if no relation table is used (many->1) */
            'external' => 'investmgmt_option_note_doc',
            'self'     => 'investmgmt_option_note_option',
        ],
        'amountGroups' => [
            'mapper'   => AmountGroupMapper::class,
            'table'    => 'investmgmt_amount_group',
            'self'     => 'investmgmt_amount_group_option',
            'external' => null,
        ],
        'attributes' => [
            'mapper'   => InvestmentObjectAttributeMapper::class,
            'table'    => 'investmgmt_option_attr',
            'self'     => 'investmgmt_option_attr_type',
            'external' => null,
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'investmgmt_option';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'investmgmt_option_id';
}
