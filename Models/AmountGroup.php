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

use phpOMS\Localization\BaseStringL11nType;
use phpOMS\Stdlib\Base\FloatInt;

/**
 * Costs/Earnings.
 *
 * @package Modules\InvestmentManagement\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class AmountGroup
{
    public int $id = 0;

    public string $name = '';

    /**
     * Amounts
     *
     * @var Amount[]
     * @since 1.0.0
     */
    public array $amounts = [];

    public BaseStringL11nType $type;

    public int $option = 0;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->type = new BaseStringL11nType();
    }

    public function sum() : FloatInt
    {
        $sum = new FloatInt();
        foreach ($this->amounts as $value) {
            $sum->add($value->amount);
        }

        return $sum;
    }
}
