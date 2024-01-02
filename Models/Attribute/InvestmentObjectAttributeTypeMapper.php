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

use Modules\Attribute\Models\AttributeType;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Investment mapper class.
 *
 * @package Modules\InvestmentManagement\Models\Attribute
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of AttributeType
 * @extends DataMapperFactory<T>
 */
final class InvestmentObjectAttributeTypeMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'investmgmt_attr_type_id'         => ['name' => 'investmgmt_attr_type_id',       'type' => 'int',    'internal' => 'id'],
        'investmgmt_attr_type_name'       => ['name' => 'investmgmt_attr_type_name',     'type' => 'string', 'internal' => 'name', 'autocomplete' => true],
        'investmgmt_attr_type_datatype'   => ['name' => 'investmgmt_attr_type_datatype',   'type' => 'int',    'internal' => 'datatype'],
        'investmgmt_attr_type_fields'     => ['name' => 'investmgmt_attr_type_fields',   'type' => 'int',    'internal' => 'fields'],
        'investmgmt_attr_type_custom'     => ['name' => 'investmgmt_attr_type_custom',   'type' => 'bool',   'internal' => 'custom'],
        'investmgmt_attr_type_pattern'    => ['name' => 'investmgmt_attr_type_pattern',  'type' => 'string', 'internal' => 'validationPattern'],
        'investmgmt_attr_type_required'   => ['name' => 'investmgmt_attr_type_required', 'type' => 'bool',   'internal' => 'isRequired'],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'l11n' => [
            'mapper'   => InvestmentObjectAttributeTypeL11nMapper::class,
            'table'    => 'investmgmt_attr_type_l11n',
            'self'     => 'investmgmt_attr_type_l11n_type',
            'column'   => 'content',
            'external' => null,
        ],
        'defaults' => [
            'mapper'   => InvestmentObjectAttributeValueMapper::class,
            'table'    => 'investmgmt_option_attr_default',
            'self'     => 'investmgmt_option_attr_default_type',
            'external' => 'investmgmt_option_attr_default_value',
        ],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = AttributeType::class;

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'investmgmt_attr_type';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'investmgmt_attr_type_id';
}
