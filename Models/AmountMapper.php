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
 * @template T of Amount
 * @extends DataMapperFactory<T>
 */
final class AmountMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'investmgmt_amount_id'                 => ['name' => 'investmgmt_amount_id',         'type' => 'int',      'internal' => 'id'],
        'investmgmt_amount_name'               => ['name' => 'investmgmt_amount_name',      'type' => 'string',   'internal' => 'name'],
        'investmgmt_amount_amount'             => ['name' => 'investmgmt_amount_amount',      'type' => 'Serializable',   'internal' => 'type'],
        'investmgmt_amount_date'               => ['name' => 'investmgmt_amount_date',      'type' => 'DateTime',   'internal' => 'date'],
        'investmgmt_amount_group'              => ['name' => 'investmgmt_amount_group',      'type' => 'int',   'internal' => 'group'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'investmgmt_amount';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'investmgmt_amount_id';
}
