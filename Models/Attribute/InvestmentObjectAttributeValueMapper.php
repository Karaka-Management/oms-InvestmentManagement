<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\InvestmentManagement\Models\Attribute
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\InvestmentManagement\Models\Attribute;

use Modules\Attribute\Models\AttributeValue;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Investment mapper class.
 *
 * @package Modules\InvestmentManagement\Models\Attribute
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of AttributeValue
 * @extends DataMapperFactory<T>
 */
final class InvestmentObjectAttributeValueMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'investmgmt_attr_value_id'       => ['name' => 'investmgmt_attr_value_id',       'type' => 'int',      'internal' => 'id'],
        'investmgmt_attr_value_default'  => ['name' => 'investmgmt_attr_value_default',  'type' => 'bool',     'internal' => 'isDefault'],
        'investmgmt_attr_value_valueStr' => ['name' => 'investmgmt_attr_value_valueStr', 'type' => 'string',   'internal' => 'valueStr'],
        'investmgmt_attr_value_valueInt' => ['name' => 'investmgmt_attr_value_valueInt', 'type' => 'int',      'internal' => 'valueInt'],
        'investmgmt_attr_value_valueDec' => ['name' => 'investmgmt_attr_value_valueDec', 'type' => 'float',    'internal' => 'valueDec'],
        'investmgmt_attr_value_valueDat' => ['name' => 'investmgmt_attr_value_valueDat', 'type' => 'DateTime', 'internal' => 'valueDat'],
        'investmgmt_attr_value_unit'     => ['name' => 'investmgmt_attr_value_unit', 'type' => 'string', 'internal' => 'unit'],
        'investmgmt_attr_value_deptype'  => ['name' => 'investmgmt_attr_value_deptype', 'type' => 'int', 'internal' => 'dependingAttributeType'],
        'investmgmt_attr_value_depvalue' => ['name' => 'investmgmt_attr_value_depvalue', 'type' => 'int', 'internal' => 'dependingAttributeValue'],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'l11n' => [
            'mapper'   => InvestmentObjectAttributeValueL11nMapper::class,
            'table'    => 'investmgmt_attr_value_l11n',
            'self'     => 'investmgmt_attr_value_l11n_value',
            'column'   => 'content',
            'external' => null,
        ],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = AttributeValue::class;

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'investmgmt_attr_value';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'investmgmt_attr_value_id';
}
