<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\HumanResourceTimeRecording
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
$investments = $this->data['investments'] ?? [];

echo $this->data['nav']->render(); ?>
<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Investments'); ?><i class="g-icon download btn end-xs">download</i></div>
            <div class="slider">
            <table id="iInvestmentList" class="default sticky">
                <thead>
                <tr>
                    <td>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                        <label for="iInvestmentList-sort-1">
                            <input type="radio" name="iInvestmentList-sort" id="iInvestmentList-sort-1">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iInvestmentList-sort-2">
                            <input type="radio" name="iInvestmentList-sort" id="iInvestmentList-sort-2">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td><?= $this->getHtml('Status'); ?>
                        <label for="iInvestmentList-sort-3">
                            <input type="radio" name="iInvestmentList-sort" id="iInvestmentList-sort-3">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iInvestmentList-sort-4">
                            <input type="radio" name="iInvestmentList-sort" id="iInvestmentList-sort-4">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td class="wf-100"><?= $this->getHtml('Name'); ?>
                        <label for="iInvestmentList-sort-5">
                            <input type="radio" name="iInvestmentList-sort" id="iInvestmentList-sort-5">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iInvestmentList-sort-6">
                            <input type="radio" name="iInvestmentList-sort" id="iInvestmentList-sort-6">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td><?= $this->getHtml('Creator'); ?>
                        <label for="iInvestmentList-sort-7">
                            <input type="radio" name="iInvestmentList-sort" id="iInvestmentList-sort-7">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iInvestmentList-sort-8">
                            <input type="radio" name="iInvestmentList-sort" id="iInvestmentList-sort-8">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                <tbody>
                <?php
                    $count = 0;
                    foreach ($investments as $key => $value) :
                        ++$count;
                        $url = UriFactory::build('{/base}/finance/investment/view?{?}&id=' . $value->id);
                ?>
                <tr data-href="<?= $url; ?>">
                    <td>
                    <td data-label="<?= $this->getHtml('ID', '0', '0'); ?>"><a href="<?= $url; ?>"><?= $value->id; ?></a>
                    <td data-label="<?= $this->getHtml('Status'); ?>"><a href="<?= $url; ?>"><?= $this->getHtml(':status' . $value->status); ?></a>
                    <td data-label="<?= $this->getHtml('Title'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->name); ?></a>
                    <td data-label="<?= $this->getHtml('Creator'); ?>"><a class="content" href="<?= UriFactory::build('{/base}/profile/view?{?}&for=' . $value->createdBy->id); ?>"><?= $this->printHtml($this->renderUserName('%3$s %2$s %1$s', [$value->createdBy->name1, $value->createdBy->name2, $value->createdBy->name3, $value->createdBy->login ?? ''])); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="8" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
            </div>
        </section>
    </div>
</div>
