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

use Modules\Admin\Models\Account;
use Modules\Admin\Models\NullAccount;
use phpOMS\Business\Finance\DepreciationType;
use phpOMS\Localization\BaseStringL11nType;

/**
 * Investment model.
 *
 * @package Modules\InvestmentManagement\Models
 * @license OMS License 2.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Investment
{
    public int $id = 0;

    public string $name = '';

    public string $description = '';

    public int $depreciationType = DepreciationType::NONE;

    public int $status = InvestmentStatus::DRAFT;

    public ?BaseStringL11nType $type = null;

    public array $options = [];

    /**
     * Income
     */
    public array $ammortization = [];

    public Account $createdBy;

    public \DateTimeImmutable $createdAt;

    public \DateTime $performanceDate;

    public array $attributeTypes = [];

    public ?int $unit = null;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->createdBy       = new NullAccount();
        $this->createdAt       = new \DateTimeImmutable('now');
        $this->performanceDate = new \DateTime('now');
    }

    use \Modules\Media\Models\MediaListTrait;
    use \Modules\Editor\Models\EditorDocListTrait;
    use \Modules\Attribute\Models\AttributeHolderTrait;
}
