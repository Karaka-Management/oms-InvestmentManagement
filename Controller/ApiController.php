<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\InvestmentManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\InvestmentManagement\Controller;

use Modules\Admin\Models\NullAccount;
use Modules\InvestmentManagement\Models\Amount;
use Modules\InvestmentManagement\Models\AmountGroup;
use Modules\InvestmentManagement\Models\AmountTypeL11nMapper;
use Modules\InvestmentManagement\Models\AmountTypeMapper;
use Modules\InvestmentManagement\Models\Investment;
use Modules\InvestmentManagement\Models\InvestmentMapper;
use Modules\InvestmentManagement\Models\InvestmentObject;
use Modules\InvestmentManagement\Models\InvestmentObjectMapper;
use Modules\InvestmentManagement\Models\InvestmentStatus;
use Modules\ItemManagement\Models\NullItem;
use Modules\Media\Models\CollectionMapper;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\NullMedia;
use Modules\Media\Models\PathSettings;
use Modules\Media\Models\Reference;
use Modules\Media\Models\ReferenceMapper;
use Modules\SupplierManagement\Models\NullSupplier;
use phpOMS\Localization\BaseStringL11n;
use phpOMS\Localization\BaseStringL11nType;
use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Localization\NullBaseStringL11nType;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Stdlib\Base\FloatInt;

/**
 * InvestmentManagement class.
 *
 * @package Modules\InvestmentManagement
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @feature Comparison/calculations
 *      Add calculation which compares investment options (costs, profit, break-even)
 *      https://github.com/Karaka-Management/oms-InvestmentManagement/issues/1
 */
final class ApiController extends Controller
{
    /**
     * Api method to create a investment
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiInvestmentCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateInvestmentCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var Investment $investment */
        $investment = $this->createInvestmentFromRequest($request);
        $this->createModel($request->header->account, $investment, InvestmentMapper::class, 'investment', $request->getOrigin());

        if (!empty($request->files)
            || !empty($request->getDataJson('media'))
        ) {
            $this->createInvestmentMedia($investment, $request);
        }

        $this->createStandardCreateResponse($request, $response, $investment);
    }

    /**
     * Method to create investment from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return Investment Returns the created investment from the request
     *
     * @since 1.0.0
     */
    public function createInvestmentFromRequest(RequestAbstract $request) : Investment
    {
        $investment                  = new Investment();
        $investment->name            = $request->getDataString('name') ?? '';
        $investment->description     = $request->getDataString('description') ?? '';
        $investment->status          = InvestmentStatus::tryFromValue($request->getDataInt('status')) ?? InvestmentStatus::DRAFT;
        $investment->description     = $request->getDataString('description') ?? '';
        $investment->unit            = $request->getDataInt('unit') ?? $this->app->unitId;
        $investment->createdBy       = new NullAccount($request->header->account);
        $investment->performanceDate = $request->getDataDateTime('performance') ?? new \DateTime($investment->createdAt->format('Y-m-d H:i:s'));
        //$investment->type     = new NullBaseStringL11nType((int) ($request->getDataInt('type') ?? 0));

        return $investment;
    }

    /**
     * Create media files for investment
     *
     * @param Investment      $investment Investment
     * @param RequestAbstract $request    Request incl. media do upload
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function createInvestmentMedia(Investment $investment, RequestAbstract $request) : void
    {
        $path = $this->createInvestmentDir($investment);

        $collection = null;

        if (!empty($uploadedFiles = $request->files)) {
            $uploaded = $this->app->moduleManager->get('Media', 'Api')->uploadFiles(
                names: [],
                fileNames: [],
                files: $uploadedFiles,
                account: $request->header->account,
                basePath: __DIR__ . '/../../../Modules/Media/Files' . $path,
                virtualPath: $path,
                pathSettings: PathSettings::FILE_PATH
            );

            foreach ($uploaded as $media) {
                $this->createModelRelation(
                    $request->header->account,
                    $investment->id,
                    $media->id,
                    InvestmentMapper::class,
                    'files',
                    '',
                    $request->getOrigin()
                );

                if ($collection === null) {
                    /** @var \Modules\Media\Models\Collection $collection */
                    $collection = MediaMapper::getParentCollection($path)->limit(1)->execute();

                    if ($collection->id === 0) {
                        $collection = $this->app->moduleManager->get('Media')->createRecursiveMediaCollection(
                            $path,
                            $request->header->account,
                            __DIR__ . '/../../../Modules/Media/Files' . $path
                        );
                    }
                }

                $this->createModelRelation(
                    $request->header->account,
                    $collection->id,
                    $media->id,
                    CollectionMapper::class,
                    'sources',
                    '',
                    $request->getOrigin()
                );
            }
        }

        $mediaFiles = $request->getDataJson('media');
        foreach ($mediaFiles as $file) {
            /** @var \Modules\Media\Models\Media $media */
            $media = MediaMapper::get()->where('id', (int) $file)->limit(1)->execute();

            $this->createModelRelation(
                $request->header->account,
                $investment->id,
                $media->id,
                InvestmentMapper::class,
                'files',
                '',
                $request->getOrigin()
            );

            $ref            = new Reference();
            $ref->name      = $media->name;
            $ref->source    = new NullMedia($media->id);
            $ref->createdBy = new NullAccount($request->header->account);
            $ref->setVirtualPath($path);

            $this->createModel($request->header->account, $ref, ReferenceMapper::class, 'media_reference', $request->getOrigin());

            if ($collection === null) {
                /** @var \Modules\Media\Models\Collection $collection */
                $collection = MediaMapper::getParentCollection($path)->limit(1)->execute();

                if ($collection->id === 0) {
                    $collection = $this->app->moduleManager->get('Media')->createRecursiveMediaCollection(
                        $path,
                        $request->header->account,
                        __DIR__ . '/../../../Modules/Media/Files' . $path
                    );
                }
            }

            $this->createModelRelation(
                $request->header->account,
                $collection->id,
                $ref->id,
                CollectionMapper::class,
                'sources',
                '',
                $request->getOrigin()
            );
        }
    }

    /**
     * Api method to create a bill
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiMediaAddToInvestment(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateMediaAddToInvestment($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidAddResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\InvestmentManagement\Models\Investment $investment */
        $investment = InvestmentMapper::get()->where('id', (int) $request->getData('investment'))->execute();
        $path       = $this->createInvestmentDir($investment);

        $uploaded = [];
        if (!empty($uploadedFiles = $request->files)) {
            $uploaded = $this->app->moduleManager->get('Media', 'Api')->uploadFiles(
                names: [],
                fileNames: [],
                files: $uploadedFiles,
                account: $request->header->account,
                basePath: __DIR__ . '/../../../Modules/Media/Files' . $path,
                virtualPath: $path,
                pathSettings: PathSettings::FILE_PATH,
                hasAccountRelation: false,
                readContent: $request->getDataBool('parse_content') ?? false
            );

            $collection = null;
            foreach ($uploaded as $media) {
                $this->createModelRelation(
                    $request->header->account,
                    $investment->id,
                    $media->id,
                    InvestmentMapper::class,
                    'files',
                    '',
                    $request->getOrigin()
                );

                if ($request->hasData('type')) {
                    $this->createModelRelation(
                        $request->header->account,
                        $media->id,
                        $request->getDataInt('type'),
                        MediaMapper::class,
                        'types',
                        '',
                        $request->getOrigin()
                    );
                }

                if ($collection === null) {
                    /** @var \Modules\Media\Models\Collection $collection */
                    $collection = MediaMapper::getParentCollection($path)->limit(1)->execute();

                    if ($collection->id === 0) {
                        $collection = $this->app->moduleManager->get('Media')->createRecursiveMediaCollection(
                            $path,
                            $request->header->account,
                            __DIR__ . '/../../../Modules/Media/Files' . $path,
                        );
                    }
                }

                $this->createModelRelation(
                    $request->header->account,
                    $collection->id,
                    $media->id,
                    CollectionMapper::class,
                    'sources',
                    '',
                    $request->getOrigin()
                );
            }
        }

        $mediaFiles = $request->getDataJson('media');
        foreach ($mediaFiles as $media) {
            $this->createModelRelation(
                $request->header->account,
                $investment->id,
                (int) $media,
                InvestmentMapper::class,
                'files',
                '',
                $request->getOrigin()
            );
        }

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Media', 'Media added to investment.', [
            'upload' => $uploaded,
            'media'  => $mediaFiles,
        ]);
    }

    /**
     * Method to validate bill creation from request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateMediaAddToInvestment(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['media'] = (!$request->hasData('media') && empty($request->files)))
            || ($val['investment'] = !$request->hasData('investment'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Validate investment create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateInvestmentCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['name'] = !$request->hasData('name'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Create media directory path
     *
     * @param Investment $investment Investment
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function createInvestmentDir(Investment $investment) : string
    {
        return '/Modules/InvestmentManagement/Investment/'
            . $this->app->unitId . '/'
            . $investment->id;
    }

    /**
     * Create media directory path
     *
     * @param InvestmentObject $object Investment
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function createInvestmentObjectDir(InvestmentObject $object) : string
    {
        return '/Modules/InvestmentManagement/Investment/'
            . $this->app->unitId . '/'
            . $object->investment;
    }

    /**
     * Api method to create a investment
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiInvestmentOptionCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateInvestmentOptionCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var InvestmentObject $investment */
        $investment = $this->createInvestmentOptionFromRequest($request);
        $this->createModel($request->header->account, $investment, InvestmentObjectMapper::class, 'investment_option', $request->getOrigin());

        if (!empty($request->files)
            || !empty($request->getDataJson('media'))
        ) {
            $this->createInvestmentObjectMedia($investment, $request);
        }

        $this->createStandardCreateResponse($request, $response, $investment);
    }

    /**
     * Validate investment create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateInvestmentOptionCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['name'] = !$request->hasData('name'))
            || ($val['investment'] = !$request->hasData('investment'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Method to create investment from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return InvestmentObject Returns the created investment from the request
     *
     * @since 1.0.0
     */
    public function createInvestmentOptionFromRequest(RequestAbstract $request) : InvestmentObject
    {
        $investment               = new InvestmentObject();
        $investment->name         = $request->getDataString('name') ?? '';
        $investment->description  = $request->getDataString('description') ?? '';
        $investment->link         = $request->getDataString('link') ?? '';
        $investment->investment   = (int) $request->getData('investment');
        $investment->parent       = $request->getDataInt('parent');
        $investment->supplier     = $request->hasData('supplier') ? new NullSupplier((int) $request->getData('supplier')) : null;
        $investment->supplierName = $request->getDataString('suppliername') ?? '';
        $investment->item         = $request->hasData('item') ? new NullItem((int) $request->getData('item')) : null;

        // @todo reconsider the following lines. This seems rather complicated.
        if ($request->hasData('amount')) {
            /** @var BaseStringL11nType[] $types */
            $types = AmountTypeMapper::getAll()->executeGetArray();

            foreach ($types as $type) {
                if ($type->title === 'costs') {
                    $defaultGroup       = new AmountGroup();
                    $defaultGroup->name = 'Purchase Price'; // @todo replace with api l11n
                    $defaultGroup->type = new NullBaseStringL11nType($type->id);

                    $amount         = new Amount();
                    $amount->amount = new FloatInt((int) $request->getDataInt('amount'));

                    $defaultGroup->amounts[] = $amount;

                    $investment->amountGroups[] = $defaultGroup;
                } elseif ($type->title === 'cashflow') {
                    $defaultGroup       = new AmountGroup();
                    $defaultGroup->name = 'Cashflow'; // @todo replace with api l11n
                    $defaultGroup->type = new NullBaseStringL11nType($type->id);

                    // @todo calculate date based on performance date + offer conditions / 30 days
                    $amount         = new Amount();
                    $amount->amount = new FloatInt((int) $request->getDataInt('amount'));

                    $defaultGroup->amounts[] = $amount;

                    $investment->amountGroups[] = $defaultGroup;
                } elseif ($type->title === 'depreciation') {
                    $defaultGroup       = new AmountGroup();
                    $defaultGroup->name = 'Depreciation'; // @todo replace with api l11n
                    $defaultGroup->type = new NullBaseStringL11nType($type->id);

                    // @todo calculate automatic depreciation;
                    $amount         = new Amount();
                    $amount->amount = new FloatInt((int) $request->getDataInt('amount'));

                    $defaultGroup->amounts[] = $amount;

                    $investment->amountGroups[] = $defaultGroup;
                }
            }
        }

        return $investment;
    }

    /**
     * Api method to create a investment
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiAmountTypeCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAmountTypeCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11nType $amount */
        $amount = $this->createAmountTypeFromRequest($request);
        $this->createModel($request->header->account, $amount, AmountTypeMapper::class, 'amount_type', $request->getOrigin());

        $this->createStandardCreateResponse($request, $response, $amount);
    }

    /**
     * Create media files for investment
     *
     * @param InvestmentObject $investment Investment
     * @param RequestAbstract  $request    Request incl. media do upload
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function createInvestmentObjectMedia(InvestmentObject $investment, RequestAbstract $request) : void
    {
        $path = $this->createInvestmentObjectDir($investment);

        $collection = null;

        if (!empty($uploadedFiles = $request->files)) {
            $uploaded = $this->app->moduleManager->get('Media', 'Api')->uploadFiles(
                names: [],
                fileNames: [],
                files: $uploadedFiles,
                account: $request->header->account,
                basePath: __DIR__ . '/../../../Modules/Media/Files' . $path,
                virtualPath: $path,
                pathSettings: PathSettings::FILE_PATH
            );

            foreach ($uploaded as $media) {
                $this->createModelRelation(
                    $request->header->account,
                    $investment->id,
                    $media->id,
                    InvestmentObjectMapper::class,
                    'files',
                    '',
                    $request->getOrigin()
                );

                if ($collection === null) {
                    /** @var \Modules\Media\Models\Collection $collection */
                    $collection = MediaMapper::getParentCollection($path)->limit(1)->execute();

                    if ($collection->id === 0) {
                        $collection = $this->app->moduleManager->get('Media')->createRecursiveMediaCollection(
                            $path,
                            $request->header->account,
                            __DIR__ . '/../../../Modules/Media/Files' . $path
                        );
                    }
                }

                $this->createModelRelation(
                    $request->header->account,
                    $collection->id,
                    $media->id,
                    CollectionMapper::class,
                    'sources',
                    '',
                    $request->getOrigin()
                );
            }
        }

        $mediaFiles = $request->getDataJson('media');
        foreach ($mediaFiles as $file) {
            /** @var \Modules\Media\Models\Media $media */
            $media = MediaMapper::get()->where('id', (int) $file)->limit(1)->execute();

            $this->createModelRelation(
                $request->header->account,
                $investment->id,
                $media->id,
                InvestmentObjectMapper::class,
                'files',
                '',
                $request->getOrigin()
            );

            $ref            = new Reference();
            $ref->name      = $media->name;
            $ref->source    = new NullMedia($media->id);
            $ref->createdBy = new NullAccount($request->header->account);
            $ref->setVirtualPath($path);

            $this->createModel($request->header->account, $ref, ReferenceMapper::class, 'media_reference', $request->getOrigin());

            if ($collection === null) {
                /** @var \Modules\Media\Models\Collection $collection */
                $collection = MediaMapper::getParentCollection($path)->limit(1)->execute();

                if ($collection->id === 0) {
                    $collection = $this->app->moduleManager->get('Media')->createRecursiveMediaCollection(
                        $path,
                        $request->header->account,
                        __DIR__ . '/../../../Modules/Media/Files' . $path
                    );
                }
            }

            $this->createModelRelation(
                $request->header->account,
                $collection->id,
                $ref->id,
                CollectionMapper::class,
                'sources',
                '',
                $request->getOrigin()
            );
        }
    }

    /**
     * Api method to create a bill
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiMediaAddToInvestmentObject(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateMediaAddToInvestmentObject($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidAddResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\InvestmentManagement\Models\InvestmentObject $investment */
        $investment = InvestmentObjectMapper::get()->where('id', (int) $request->getData('option'))->execute();
        $path       = $this->createInvestmentObjectDir($investment);

        $uploaded = [];
        if (!empty($uploadedFiles = $request->files)) {
            $uploaded = $this->app->moduleManager->get('Media', 'Api')->uploadFiles(
                names: [],
                fileNames: [],
                files: $uploadedFiles,
                account: $request->header->account,
                basePath: __DIR__ . '/../../../Modules/Media/Files' . $path,
                virtualPath: $path,
                pathSettings: PathSettings::FILE_PATH,
                hasAccountRelation: false,
                readContent: $request->getDataBool('parse_content') ?? false
            );

            $collection = null;
            foreach ($uploaded as $media) {
                $this->createModelRelation(
                    $request->header->account,
                    $investment->id,
                    $media->id,
                    InvestmentObjectMapper::class,
                    'files',
                    '',
                    $request->getOrigin()
                );

                if ($request->hasData('type')) {
                    $this->createModelRelation(
                        $request->header->account,
                        $media->id,
                        $request->getDataInt('type'),
                        MediaMapper::class,
                        'types',
                        '',
                        $request->getOrigin()
                    );
                }

                if ($collection === null) {
                    /** @var \Modules\Media\Models\Collection $collection */
                    $collection = MediaMapper::getParentCollection($path)->limit(1)->execute();

                    if ($collection->id === 0) {
                        $collection = $this->app->moduleManager->get('Media')->createRecursiveMediaCollection(
                            $path,
                            $request->header->account,
                            __DIR__ . '/../../../Modules/Media/Files' . $path,
                        );
                    }
                }

                $this->createModelRelation(
                    $request->header->account,
                    $collection->id,
                    $media->id,
                    CollectionMapper::class,
                    'sources',
                    '',
                    $request->getOrigin()
                );
            }
        }

        $mediaFiles = $request->getDataJson('media');
        foreach ($mediaFiles as $media) {
            $this->createModelRelation(
                $request->header->account,
                $investment->id,
                (int) $media,
                InvestmentObjectMapper::class,
                'files',
                '',
                $request->getOrigin()
            );
        }

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Media', 'Media added to investment.', [
            'upload' => $uploaded,
            'media'  => $mediaFiles,
        ]);
    }

    /**
     * Method to validate bill creation from request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateMediaAddToInvestmentObject(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['media'] = (!$request->hasData('media') && empty($request->files)))
            || ($val['investment'] = !$request->hasData('investment'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Method to create investment from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11nType Returns the created investment from the request
     *
     * @since 1.0.0
     */
    public function createAmountTypeFromRequest(RequestAbstract $request) : BaseStringL11nType
    {
        $type        = new BaseStringL11nType();
        $type->title = $request->getDataString('name') ?? '';
        $type->setL11n(
            $request->getDataString('title') ?? '',
            ISO639x1Enum::tryFromValue($request->getDataString('language')) ?? ISO639x1Enum::_EN
        );

        return $type;
    }

    /**
     * Validate investment create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateAmountTypeCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['name'] = !$request->hasData('name'))
            || ($val['title'] = !$request->hasData('title'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create investment attribute l11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiAmountTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAmountTypeL11nCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $typeL11n = $this->createAmountTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $typeL11n, AmountTypeL11nMapper::class, 'amount_type_l11n', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $typeL11n);
    }

    /**
     * Method to create investment attribute l11n from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11n
     *
     * @since 1.0.0
     */
    private function createAmountTypeL11nFromRequest(RequestAbstract $request) : BaseStringL11n
    {
        $typeL11n           = new BaseStringL11n();
        $typeL11n->ref      = $request->getDataInt('type') ?? 0;
        $typeL11n->language = ISO639x1Enum::tryFromValue($request->getDataString('language')) ?? $request->header->l11n->language;
        $typeL11n->content  = $request->getDataString('title') ?? '';

        return $typeL11n;
    }

    /**
     * Validate investment attribute l11n create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateAmountTypeL11nCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = !$request->hasData('title'))
            || ($val['type'] = !$request->hasData('type'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create notes
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateNoteCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $request->setData('virtualpath', '/Modules/InvestmentManagement/Investment/' . $request->getData('id'), true);
        $this->app->moduleManager->get('Editor', 'Api')->apiEditorCreate($request, $response, $data);

        if ($response->header->status !== RequestStatusCode::R_200) {
            return;
        }

        $responseData = $response->getDataArray($request->uri->__toString());
        if (!\is_array($responseData)) {
            return;
        }

        $model = $responseData['response'];
        $this->createModelRelation($request->header->account, (int) $request->getData('id'), $model->id, InvestmentMapper::class, 'notes', '', $request->getOrigin());
    }

    /**
     * Validate item note create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateNoteCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to update note
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteEdit(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        $this->app->moduleManager->get('Editor', 'Api')->apiEditorUpdate($request, $response, $data);

        if ($response->header->status !== RequestStatusCode::R_200) {
            return;
        }

        $responseData = $response->getDataArray($request->uri->__toString());
        if (!\is_array($responseData)) {
            return;
        }
    }
}
