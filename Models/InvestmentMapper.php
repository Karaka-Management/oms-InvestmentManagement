<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\InvestmentManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\InvestmentManagement\Models;

use Modules\Admin\Models\AccountMapper;
use Modules\Editor\Models\EditorDocMapper;
use Modules\Media\Models\MediaMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\InvestmentManagement\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of Investment
 * @extends DataMapperFactory<T>
 */
final class InvestmentMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'investmgmt_investment_id'          => ['name' => 'investmgmt_investment_id',         'type' => 'int',      'internal' => 'id'],
        'investmgmt_investment_name'        => ['name' => 'investmgmt_investment_name',      'type' => 'string',   'internal' => 'name'],
        'investmgmt_investment_description' => ['name' => 'investmgmt_investment_description',       'type' => 'string',   'internal' => 'description'],
        'investmgmt_investment_status'      => ['name' => 'investmgmt_investment_status',      'type' => 'int',   'internal' => 'status'],
        'investmgmt_investment_unit'        => ['name' => 'investmgmt_investment_unit',      'type' => 'int',   'internal' => 'unit'],
        'investmgmt_investment_created_by'  => ['name' => 'investmgmt_investment_created_by', 'type' => 'int',      'internal' => 'createdBy', 'readonly' => true],
        'investmgmt_investment_performance' => ['name' => 'investmgmt_investment_performance', 'type' => 'DateTime', 'internal' => 'performanceDate'],
        'investmgmt_investment_created_at'  => ['name' => 'investmgmt_investment_created_at', 'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'type' => [
            'mapper'   => InvestmentTypeMapper::class,
            'external' => 'investmgmt_investment_type',
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
            'table'    => 'investmgmt_investment_media',         /* table of the related object, null if no relation table is used (many->1) */
            'external' => 'investmgmt_investment_media_media',
            'self'     => 'investmgmt_investment_media_investment',
        ],
        'notes' => [
            'mapper'   => EditorDocMapper::class,       /* mapper of the related object */
            'table'    => 'investmgmt_investment_note',         /* table of the related object, null if no relation table is used (many->1) */
            'external' => 'investmgmt_investment_note_doc',
            'self'     => 'investmgmt_investment_note_investment',
        ],
        'options' => [
            'mapper'   => InvestmentObjectMapper::class,
            'table'    => 'investmgmt_option',
            'self'     => 'investmgmt_option_investment',
            'external' => null,
        ],
    ];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:class-string, external:string, column?:string, by?:string}>
     * @since 1.0.0
     */
    public const BELONGS_TO = [
        'createdBy' => [
            'mapper'   => AccountMapper::class,
            'external' => 'investmgmt_investment_created_by',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'investmgmt_investment';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    public const CREATED_AT = 'investmgmt_investment_created_at';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'investmgmt_investment_id';
}
