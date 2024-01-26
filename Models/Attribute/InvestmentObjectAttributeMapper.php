<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\InvestmentManagement\Models\Attribute
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\InvestmentManagement\Models\Attribute;

use Modules\Attribute\Models\Attribute;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Investment mapper class.
 *
 * @package Modules\InvestmentManagement\Models\Attribute
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of Attribute
 * @extends DataMapperFactory<T>
 */
final class InvestmentObjectAttributeMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'investmgmt_option_attr_id'     => ['name' => 'investmgmt_option_attr_id',    'type' => 'int', 'internal' => 'id'],
        'investmgmt_option_attr_option' => ['name' => 'investmgmt_option_attr_option',  'type' => 'int', 'internal' => 'ref'],
        'investmgmt_option_attr_type'   => ['name' => 'investmgmt_option_attr_type',  'type' => 'int', 'internal' => 'type'],
        'investmgmt_option_attr_value'  => ['name' => 'investmgmt_option_attr_value', 'type' => 'int', 'internal' => 'value'],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'type' => [
            'mapper'   => InvestmentObjectAttributeTypeMapper::class,
            'external' => 'investmgmt_option_attr_type',
        ],
        'value' => [
            'mapper'   => InvestmentObjectAttributeValueMapper::class,
            'external' => 'investmgmt_option_attr_value',
        ],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = Attribute::class;

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'investmgmt_option_attr';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'investmgmt_option_attr_id';
}
