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

use Modules\ItemManagement\Models\Item;
use Modules\SupplierManagement\Models\Supplier;

/**
 * Investment object.
 *
 * @package Modules\InvestmentManagement\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class InvestmentObject
{
    public int $id = 0;

    public string $name = '';

    public string $description = '';

    public ?Supplier $supplier = null;

    public string $supplierName = '';

    public string $link = '';

    /**
     * Costs / Revenue
     *
     * @var AmountGroup[]
     * @since 1.0.0
     */
    public array $amountGroups = [];

    public bool $approved = false;

    public array $attributes = [];

    public ?int $parent = null;

    public int $investment = 0;

    public ?Item $item = null;

    /**
     * Get amount group by type name
     *
     * @param string $type Type name
     *
     * @return AmountGroup
     *
     * @since 1.0.0
     */
    public function getAmountByTypeName(string $type) : AmountGroup
    {
        foreach ($this->amountGroups as $group) {
            if ($group->type->title === $type) {
                return $group;
            }
        }

        return new NullAmountGroup();
    }

    use \Modules\Media\Models\MediaListTrait;
    use \Modules\Editor\Models\EditorDocListTrait;
    use \Modules\Attribute\Models\AttributeHolderTrait;
}
