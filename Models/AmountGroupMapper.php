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

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\InvestmentManagement\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of AmountGroup
 * @extends DataMapperFactory<T>
 */
final class AmountGroupMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'investmgmt_amount_group_id'                 => ['name' => 'investmgmt_amount_group_id',         'type' => 'int',      'internal' => 'id'],
        'investmgmt_amount_group_name'               => ['name' => 'investmgmt_amount_group_name',      'type' => 'string',   'internal' => 'name'],
        'investmgmt_amount_group_type'               => ['name' => 'investmgmt_amount_group_type',      'type' => 'int',   'internal' => 'type'],
        'investmgmt_amount_group_option'             => ['name' => 'investmgmt_amount_group_option',      'type' => 'int',   'internal' => 'option'],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'type'  => [
            'mapper'     => AmountTypeMapper::class,
            'external'   => 'investmgmt_amount_group_type',
        ],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'amounts' => [
            'mapper'   => AmountMapper::class,
            'table'    => 'investmgmt_amount',
            'self'     => 'investmgmt_amount_group',
            'external' => null,
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'investmgmt_amount_group';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'investmgmt_amount_group_id';
}
