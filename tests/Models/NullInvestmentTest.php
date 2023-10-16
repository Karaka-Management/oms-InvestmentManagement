<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\InvestmentManagement\tests\Models;

use Modules\InvestmentManagement\Models\NullInvestment;

/**
 * @internal
 */
final class NullInvestmentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\InvestmentManagement\Models\NullInvestment
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\InvestmentManagement\Models\Investment', new NullInvestment());
    }

    /**
     * @covers Modules\InvestmentManagement\Models\NullInvestment
     * @group module
     */
    public function testId() : void
    {
        $null = new NullInvestment(2);
        self::assertEquals(2, $null->id);
    }

    /**
     * @covers Modules\InvestmentManagement\Models\NullInvestment
     * @group module
     */
    public function testJsonSerialize() : void
    {
        $null = new NullInvestment(2);
        self::assertEquals(['id' => 2], $null);
    }
}
