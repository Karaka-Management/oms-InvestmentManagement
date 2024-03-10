<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\HumanResourceTimeRecording
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\InvestmentManagement\Models\InvestmentStatus;
use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
$investment       = $this->data['investment'] ?? null;
$investmentStatus = InvestmentStatus::getConstants();
$files            = $investment->files;
$investmentTypes  = $this->data['types'] ?? [];

echo $this->data['nav']->render(); ?>
<div class="tabview tab-2">
    <div class="box">
        <ul class="tab-links">
            <li><label for="c-tab-1"><?= $this->getHtml('Investment'); ?></label>
            <li><label for="c-tab-2"><?= $this->getHtml('Files'); ?></label>
            <li><label for="c-tab-3"><?= $this->getHtml('Notes'); ?></label>
            <li><label for="c-tab-4"><?= $this->getHtml('Options'); ?></label>
        </ul>
    </div>
    <div class="tab-content">
        <input type="radio" id="c-tab-1" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-1' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <section class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Investment'); ?></div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="iInvestmentName"><?= $this->getHtml('Name'); ?></label>
                                <input type="text" id="iInvestmentName" name="name" value="<?= $this->printHtml($investment->name); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iInvestmentDescription"><?= $this->getHtml('Description'); ?></label>
                                <textarea id="iInvestmentDescription" name="description"><?= $this->printHtml($investment->description); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="iInvestmentStatus"><?= $this->getHtml('Status'); ?></label>
                                <select id="iInvestmentStatus" name="investment_status" disabled>
                                    <?php foreach ($investmentStatus as $status) : ?>
                                        <option value="<?= $status; ?>"<?= $status === $investment->status ? ' selected' : ''; ?>><?= $this->getHtml(':status' . $status); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iInvestmentType"><?= $this->getHtml('Type'); ?></label>
                                <select id="iInvestmentType" name="investment_type">
                                    <?php foreach ($investmentTypes as $type) : ?>
                                        <option value="<?= $type->id; ?>"<?= $investment->type->id === $type->id ? ' selected' : ''; ?>><?= $this->printHtml($type->getL11n()); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iInvestmentPurchase"><?= $this->getHtml('Purchase'); ?></label>
                                <input type="date" id="iInvestmentPurchase" name="purchaseDate" value="<?= $investment->performanceDate->format('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="portlet-foot">
                            <?php if ($investment->id === 0) : ?>
                                <input id="iCreateSubmit" type="Submit" value="<?= $this->getHtml('Create', '0', '0'); ?>">
                            <?php else : ?>
                                <input id="iSaveSubmit" type="Submit" value="<?= $this->getHtml('Save', '0', '0'); ?>">
                            <?php endif; ?>
                        </div>
                    </section>
                </div>

                <div class="col-xs-12 col-md-6">
                    <section class="portlet">
                        <div class="portlet-head">
                            <?= $this->getHtml('Options'); ?>
                            <i class="g-icon download btn end-xs">download</i>
                        </div>
                        <div class="slider">
                        <table id="iSalesClientList" class="default sticky">
                            <thead>
                            <tr>
                                <td>
                                <td><?= $this->getHtml('Name'); ?>
                                <td><?= $this->getHtml('Supplier'); ?>
                                <td><?= $this->getHtml('Price'); ?>
                                <td><?= $this->getHtml('Link'); ?>
                            <tbody>
                            <?php foreach ($investment->options as $option) : ?>
                            <tr>
                                <td><?php if ($option->approved) : ?><i class="g-icon">check</i><?php endif; ?>
                                <td><?= $this->printHtml($option->name); ?>
                                <td><?= $option->supplier === null
                                    ? $this->printHtml($option->supplierName)
                                    : $option->supplier->account->name1;
                                    ?>
                                <td><?= $this->getCurrency($option->getAmountByTypeName('costs')->sum(), '', 'medium'); ?>
                                <td><?php if (!empty($option->link)) : ?>
                                    <a class="content" target="_blank" href="<?= $option->link; ?>"><?= $this->printHtml($option->link); ?></a>
                                    <?php endif; ?>
                            <?php endforeach; ?>
                        </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <input type="radio" id="c-tab-2" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-2' ? ' checked' : ''; ?>>
        <div class="tab col-simple">
            <?= $this->data['media-upload']->render('investment-file', 'files', '', $investment->files); ?>
        </div>

        <input type="radio" id="c-tab-3" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-3' ? ' checked' : ''; ?>>
        <div class="tab col-simple">
            <?= $this->data['note']->render('investment-notes', '', $investment->notes); ?>
        </div>

        <input type="radio" id="c-tab-4" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-4' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <a class="button" href="<?= UriFactory::build('{/base}/finance/investment/option/create?investment=' . $investment->id); ?>"><?= $this->getHtml('Create', '0', '0'); ?></a>
            </div>

            <div class="row">
                <?php
                $count = 0;
                foreach ($investment->options as $option) :
                    if ($option->parent !== null) {
                        continue;
                    }

                    ++$count;
                ?>
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <section class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Option'); ?> <?= $count; ?></div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="iObjectName-<?= $count; ?>"><?= $this->getHtml('Name'); ?></label>
                                <input type="text" id="iObjectName-<?= $count; ?>" name="name" value="<?= $this->printHtml($option->name); ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label for="iObjectDescription-<?= $count; ?>"><?= $this->getHtml('Description'); ?></label>
                                <textarea id="iObjectDescription-<?= $count; ?>" name="description" disabled><?= $this->printHtml($option->description); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="iObjectLink-<?= $count; ?>"><?= $this->getHtml('Link'); ?></label>
                                <input type="text" id="iObjectLink-<?= $count; ?>" name="link" value="<?= $this->printHtml($option->link); ?>" disabled>
                            </div>

                            <div class="form-group">
                                <span class="checkbox">
                                    <label class="checkbox" for="iApproved-<?= $count; ?>">
                                        <input id="iApproved-<?= $count; ?>" type="checkbox" name="approved" value="1" disabled>
                                        <span class="checkmark"></span>
                                        <?= $this->getHtml('Approved'); ?>
                                    </label>
                                </span>
                            </div>

                            <div class="form-group">
                            <table class="default">
                                <thead>
                                    <tr>
                                        <td><?= $this->getHtml('Attributes'); ?>
                                <tbody>
                                    <?php foreach ($option->attributes as $attribute) : ?>
                                    <tr>
                                        <td>
                                    <?php endforeach; ?>
                                    <?php if (empty($option->attributes)) : ?>
                                        <tr><td colspan="1" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                                    <?php endif; ?>
                            </table>
                            </div>

                            <div class="form-group">
                            <table class="default">
                                <thead>
                                    <tr>
                                        <td><?= $this->getHtml('Amounts'); ?>
                                <tbody>
                                    <?php foreach ($option->amountGroups as $group) : ?>
                                    <tr>
                                        <td><?= $this->getCurrency($group->sum(), '', 'medium'); ?>
                                    <?php endforeach; ?>
                                    <?php if (empty($option->files)) : ?>
                                        <tr><td colspan="1" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                                    <?php endif; ?>
                            </table>
                            </div>

                            <div class="form-group">
                            <table class="default">
                                <thead>
                                    <tr>
                                        <td><?= $this->getHtml('Files'); ?>
                                <tbody>
                                    <?php foreach ($option->files as $file) : ?>
                                    <tr>
                                        <td>
                                    <?php endforeach; ?>
                                    <?php if (empty($option->files)) : ?>
                                        <tr><td colspan="1" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                                    <?php endif; ?>
                            </table>
                            </div>

                            <div class="form-group">
                            <table class="default">
                                <thead>
                                    <tr>
                                        <td><?= $this->getHtml('Notes'); ?>
                                <tbody>
                                    <?php foreach ($option->notes as $note) : ?>
                                    <tr>
                                        <td>
                                    <?php endforeach; ?>
                                    <?php if (empty($option->notes)) : ?>
                                        <tr><td colspan="1" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                                    <?php endif; ?>
                            </table>
                            </div>
                        </div>
                        <div class="portlet-foot">
                            <a class="button edit" href="<?= UriFactory::build('{/base}/finance/investment/option/view?id=' . $option->id); ?>"><?= $this->getHtml('Edit', '0', '0'); ?></a>
                        </div>
                    </section>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>
