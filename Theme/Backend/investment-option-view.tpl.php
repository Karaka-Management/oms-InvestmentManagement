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
?>
<div class="row">
    <a class="button" href="<?= UriFactory::build('{/base}/finance/investment/view?id=' . $request->uri->getQuery('id')); ?>"><?= $this->getHtml('Back', '0', '0'); ?></a>
</div>

<div class="row">
    <div class="col-xs-12 col-md-6 col-lg-4">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Option'); ?> <?= $count; ?></div>
            <div class="portlet-body">
                <div class="form-group">
                    <label for="iObjectName-<?= $count; ?>"><?= $this->getHtml('Name'); ?></label>
                    <input type="text" id="iObjectName-<?= $count; ?>" name="name" value="<?= $this->printHtml($option->name); ?>">
                </div>

                <div class="form-group">
                    <label for="iObjectDescription-<?= $count; ?>"><?= $this->getHtml('Description'); ?></label>
                    <textarea id="iObjectDescription-<?= $count; ?>" name="description"><?= $this->printHtml($option->description); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="iObjectLink-<?= $count; ?>"><?= $this->getHtml('Link'); ?></label>
                    <input type="text" id="iObjectLink-<?= $count; ?>" name="link" value="<?= $this->printHtml($option->link); ?>">
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
                <a class="button edit" href="<?= UriFactory::build('{/base}/finance/investment/object?id=' . $option->id); ?>"><?= $this->getHtml('Edit', '0', '0'); ?></a>
            </div>
        </section>
    </div>
</div>