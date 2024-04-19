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

use Modules\InvestmentManagement\Models\NullInvestmentObject;
use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
$option = $this->data['option'] ?? new NullInvestmentObject();

$isNew = $option->id === 0;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <a class="button" href="<?= UriFactory::build('{/base}/finance/investment/view?id=' . $this->request->uri->getQuery('id')); ?>"><?= $this->getHtml('Back', '0', '0'); ?></a>
        </div>
    </div>
</div>
<div class="tabview tab-2">
    <?php if (!$isNew) : ?>
    <div class="box">
        <ul class="tab-links">
            <li><label for="c-tab-1"><?= $this->getHtml('Option'); ?></label>
            <li><label for="c-tab-2"><?= $this->getHtml('Attributes'); ?></label>
            <!-- @tood implement
            <li><label for="c-tab-3"><?= $this->getHtml('Amounts'); ?></label>
            -->
            <li><label for="c-tab-4"><?= $this->getHtml('Notes'); ?></label>
            <li><label for="c-tab-5"><?= $this->getHtml('Files'); ?></label>
        </ul>
    </div>
    <?php endif; ?>
    <div class="tab-content">
        <input type="radio" id="c-tab-1" name="tabular-2"<?= $isNew || $this->request->uri->fragment === 'c-tab-1' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <section class="portlet">
                        <form id="fOption" method="<?= $isNew ? 'PUT' : 'POST'; ?>" action="<?= \phpOMS\Uri\UriFactory::build('{/api}finance/investment/option?{?}&csrf={$CSRF}'); ?>">
                        <div class="portlet-head"><?= $this->getHtml('Option'); ?></div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="iId"><?= $this->getHtml('ID', '0', '0'); ?></label>
                                <input type="text" name="id" id="iId" value="<?= $option->id; ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label for="iObjectName"><?= $this->getHtml('Name'); ?></label>
                                <input type="text" id="iObjectName" name="name" value="<?= $this->printHtml($option->name); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iObjectDescription"><?= $this->getHtml('Description'); ?></label>
                                <textarea id="iObjectDescription" name="description"><?= $this->printTextarea($option->description); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="iObjectLink"><?= $this->getHtml('Link'); ?></label>
                                <input type="text" id="iObjectLink" name="link" value="<?= $this->printHtml($option->link); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iObjectSupplier"><?= $this->getHtml('Supplier'); ?></label>
                                <div class="flex-line wf-100">
                                    <!-- @todo supplier id
                                    <div>
                                        <input type="text" id="iObjectSupplier" name="supplier" value="<?= $this->printHtml($option->supplier?->account?->name1); ?>">
                                    </div>
                                    -->
                                    <div>
                                        <input type="text" id="iObjectSupplier" name="suppliername" value="<?= $this->printHtml($option->supplierName); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="iObjectPrice"><?= $this->getHtml('Price'); ?></label>
                                <input type="number" step="any" id="iObjectPrice" name="amount" value="">
                            </div>

                            <?php if (!$isNew) : ?>
                            <div class="form-group">
                                <span class="checkbox">
                                    <label class="checkbox" for="iApproved">
                                        <input id="iApproved" type="checkbox" name="approved" value="1">
                                        <span class="checkmark"></span>
                                        <?= $this->getHtml('Approved'); ?>
                                    </label>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="portlet-foot">
                            <?php if ($isNew) : ?>
                                <input id="iCreateSubmit" type="Submit" value="<?= $this->getHtml('Create', '0', '0'); ?>">
                            <?php else : ?>
                                <input id="iSaveSubmit" type="Submit" value="<?= $this->getHtml('Save', '0', '0'); ?>">
                            <?php endif; ?>
                        </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>

        <?php if (!$isNew) : ?>
        <input type="radio" id="c-tab-2" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-2' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <?= $this->data['attributeView']->render(
                    $option->attributes,
                    $this->data['attributeTypes'] ?? [],
                    $this->data['units'] ?? [],
                    '{/api}finance/investment/option/attribute?csrf={$CSRF}',
                    $option->id
                );
                ?>
            </div>
        </div>

        <!--
        <input type="radio" id="c-tab-3" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-3' ? ' checked' : ''; ?>>
        <div class="tab">
        </div>
        -->

        <input type="radio" id="c-tab-4" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-4' ? ' checked' : ''; ?>>
        <div class="tab col-simple">
            <?= $this->data['note']->render('option-note', 'notes', $option->notes); ?>
        </div>

        <input type="radio" id="c-tab-5" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-5' ? ' checked' : ''; ?>>
        <div class="tab col-simple">
            <?= $this->data['media-upload']->render('option-file', 'files', '', $option->files); ?>
        </div>
        <?php endif; ?>
    </div>
</div>
