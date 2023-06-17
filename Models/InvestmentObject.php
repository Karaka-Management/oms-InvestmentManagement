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

/**
 * Investment object.
 *
 * @package Modules\InvestmentManagement\Models
 * @license OMS License 2.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class InvestmentObject
{
    public int $id = 0;

    public string $name = '';

    public string $description = '';

    public int $supplier = 0;

    public string $supplierName = '';

    public string $link = '';

    /**
     * Costs / Revenue
     */
    public array $amountGroups = [];

    public bool $approved = false;

    public array $attributes = [];

    public ?int $parent = null;

    public int $investment = 0;

    public ?int $item = null;

    use \Modules\Media\Models\MediaListTrait;
    use \Modules\Editor\Models\EditorDocListTrait;
}
