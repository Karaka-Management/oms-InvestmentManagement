<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\InvestmentManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\InvestmentManagement\Controller;

use Modules\Attribute\Models\Attribute;
use Modules\Attribute\Models\AttributeType;
use Modules\Attribute\Models\AttributeValue;
use Modules\InvestmentManagement\Models\Attribute\InvestmentObjectAttributeMapper;
use Modules\InvestmentManagement\Models\Attribute\InvestmentObjectAttributeTypeL11nMapper;
use Modules\InvestmentManagement\Models\Attribute\InvestmentObjectAttributeTypeMapper;
use Modules\InvestmentManagement\Models\Attribute\InvestmentObjectAttributeValueL11nMapper;
use Modules\InvestmentManagement\Models\Attribute\InvestmentObjectAttributeValueMapper;
use phpOMS\Localization\BaseStringL11n;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;

/**
 * InvestmentManagement class.
 *
 * @package Modules\InvestmentManagement
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ApiAttributeController extends Controller
{
    use \Modules\Attribute\Controller\ApiAttributeTraitController;

    /**
     * Api method to create option attribute
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
    public function apiInvestmentObjectAttributeCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $type = InvestmentObjectAttributeTypeMapper::get()->with('defaults')->where('id', (int) $request->getData('type'))->execute();

        if (!$type->repeatable) {
            $attr = InvestmentObjectAttributeMapper::count()
                ->with('type')
                ->where('type/id', (int) $request->getData('type'))
                ->where('ref', (int) $request->getData('ref'))
                ->execute();

            if ($attr > 0) {
                $response->header->status = RequestStatusCode::R_409;
                $this->createInvalidCreateResponse($request, $response, $val);

                return;
            }
        }

        $attribute = $this->createAttributeFromRequest($request, $type);
        $this->createModel($request->header->account, $attribute, InvestmentObjectAttributeMapper::class, 'attribute', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $attribute);
    }

    /**
     * Api method to create option attribute l11n
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
    public function apiInvestmentObjectAttributeTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeTypeL11nCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $attrL11n = $this->createAttributeTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $attrL11n, InvestmentObjectAttributeTypeL11nMapper::class, 'attr_type_l11n', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $attrL11n);
    }

    /**
     * Api method to create option attribute type
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
    public function apiInvestmentObjectAttributeTypeCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeTypeCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $attrType = $this->createAttributeTypeFromRequest($request);
        $this->createModel($request->header->account, $attrType, InvestmentObjectAttributeTypeMapper::class, 'attr_type', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $attrType);
    }

    /**
     * Api method to create option attribute value
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
    public function apiInvestmentObjectAttributeValueCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeValueCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\Attribute\Models\AttributeType $type */
        $type = InvestmentObjectAttributeTypeMapper::get()
            ->where('id', $request->getDataInt('type') ?? 0)
            ->execute();

        $attrValue = $this->createAttributeValueFromRequest($request, $type);
        $this->createModel($request->header->account, $attrValue, InvestmentObjectAttributeValueMapper::class, 'attr_value', $request->getOrigin());

        if ($attrValue->isDefault) {
            $this->createModelRelation(
                $request->header->account,
                (int) $request->getData('type'),
                $attrValue->id,
                InvestmentObjectAttributeTypeMapper::class, 'defaults', '', $request->getOrigin()
            );
        }

        $this->createStandardCreateResponse($request, $response, $attrValue);
    }

    /**
     * Api method to create option attribute l11n
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
    public function apiInvestmentObjectAttributeValueL11nCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeValueL11nCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $attrL11n = $this->createAttributeValueL11nFromRequest($request);
        $this->createModel($request->header->account, $attrL11n, InvestmentObjectAttributeValueL11nMapper::class, 'attr_value_l11n', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $attrL11n);
    }

    /**
     * Api method to update InvestmentObjectAttribute
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
    public function apiInvestmentObjectAttributeUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var Attribute $old */
        $old = InvestmentObjectAttributeMapper::get()
            ->with('type')
            ->with('type/defaults')
            ->with('value')
            ->where('id', (int) $request->getData('id'))
            ->execute();

        $new = $this->updateAttributeFromRequest($request, clone $old);

        if ($new->id === 0) {
            // Set response header to invalid request because of invalid data
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $new);

            return;
        }

        $this->updateModel($request->header->account, $old, $new, InvestmentObjectAttributeMapper::class, 'option_attribute', $request->getOrigin());

        if ($new->value->getValue() !== $old->value->getValue()
            && $new->type->custom
        ) {
            $this->updateModel($request->header->account, $old->value, $new->value, InvestmentObjectAttributeValueMapper::class, 'attribute_value', $request->getOrigin());
        }

        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Api method to delete InvestmentObjectAttribute
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
    public function apiInvestmentObjectAttributeDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeDelete($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidDeleteResponse($request, $response, $val);

            return;
        }

        $optionAttribute = InvestmentObjectAttributeMapper::get()
            ->with('type')
            ->where('id', (int) $request->getData('id'))
            ->execute();

        if ($optionAttribute->type->isRequired) {
            $this->createInvalidDeleteResponse($request, $response, []);

            return;
        }

        $this->deleteModel($request->header->account, $optionAttribute, InvestmentObjectAttributeMapper::class, 'option_attribute', $request->getOrigin());
        $this->createStandardDeleteResponse($request, $response, $optionAttribute);
    }

    /**
     * Api method to update InvestmentObjectAttributeTypeL11n
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
    public function apiInvestmentObjectAttributeTypeL11nUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeTypeL11nUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11n $old */
        $old = InvestmentObjectAttributeTypeL11nMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $new = $this->updateAttributeTypeL11nFromRequest($request, clone $old);

        $this->updateModel($request->header->account, $old, $new, InvestmentObjectAttributeTypeL11nMapper::class, 'option_attribute_type_l11n', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Api method to delete InvestmentObjectAttributeTypeL11n
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
    public function apiInvestmentObjectAttributeTypeL11nDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeTypeL11nDelete($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidDeleteResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11n $optionAttributeTypeL11n */
        $optionAttributeTypeL11n = InvestmentObjectAttributeTypeL11nMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $this->deleteModel($request->header->account, $optionAttributeTypeL11n, InvestmentObjectAttributeTypeL11nMapper::class, 'option_attribute_type_l11n', $request->getOrigin());
        $this->createStandardDeleteResponse($request, $response, $optionAttributeTypeL11n);
    }

    /**
     * Api method to update InvestmentObjectAttributeType
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
    public function apiInvestmentObjectAttributeTypeUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeTypeUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var AttributeType $old */
        $old = InvestmentObjectAttributeTypeMapper::get()->with('defaults')->where('id', (int) $request->getData('id'))->execute();
        $new = $this->updateAttributeTypeFromRequest($request, clone $old);

        $this->updateModel($request->header->account, $old, $new, InvestmentObjectAttributeTypeMapper::class, 'option_attribute_type', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Api method to delete InvestmentObjectAttributeType
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @todo Implement API function
     *
     * @since 1.0.0
     */
    public function apiInvestmentObjectAttributeTypeDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeTypeDelete($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidDeleteResponse($request, $response, $val);

            return;
        }

        /** @var AttributeType $optionAttributeType */
        $optionAttributeType = InvestmentObjectAttributeTypeMapper::get()->with('defaults')->where('id', (int) $request->getData('id'))->execute();
        $this->deleteModel($request->header->account, $optionAttributeType, InvestmentObjectAttributeTypeMapper::class, 'option_attribute_type', $request->getOrigin());
        $this->createStandardDeleteResponse($request, $response, $optionAttributeType);
    }

    /**
     * Api method to update InvestmentObjectAttributeValue
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
    public function apiInvestmentObjectAttributeValueUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeValueUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var AttributeValue $old */
        $old = InvestmentObjectAttributeValueMapper::get()->where('id', (int) $request->getData('id'))->execute();

        /** @var \Modules\Attribute\Models\Attribute $attr */
        $attr = InvestmentObjectAttributeMapper::get()
            ->with('type')
            ->where('id', $request->getDataInt('attribute') ?? 0)
            ->execute();

        $new = $this->updateAttributeValueFromRequest($request, clone $old, $attr);

        $this->updateModel($request->header->account, $old, $new, InvestmentObjectAttributeValueMapper::class, 'option_attribute_value', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Api method to delete InvestmentObjectAttributeValue
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
    public function apiInvestmentObjectAttributeValueDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        // @todo I don't think values can be deleted? Only Attributes
        // However, It should be possible to remove UNUSED default values
        // either here or other function?
        // if (!empty($val = $this->validateAttributeValueDelete($request))) {
        //     $response->header->status = RequestStatusCode::R_400;
        //     $this->createInvalidDeleteResponse($request, $response, $val);

        //     return;
        // }

        // /** @var \Modules\InvestmentManagement\Models\InvestmentObjectAttributeValue $optionAttributeValue */
        // $optionAttributeValue = InvestmentObjectAttributeValueMapper::get()->where('id', (int) $request->getData('id'))->execute();
        // $this->deleteModel($request->header->account, $optionAttributeValue, InvestmentObjectAttributeValueMapper::class, 'option_attribute_value', $request->getOrigin());
        // $this->createStandardDeleteResponse($request, $response, $optionAttributeValue);
    }

    /**
     * Api method to update InvestmentObjectAttributeValueL11n
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
    public function apiInvestmentObjectAttributeValueL11nUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeValueL11nUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11n $old */
        $old = InvestmentObjectAttributeValueL11nMapper::get()->where('id', (int) $request->getData('id'));
        $new = $this->updateAttributeValueL11nFromRequest($request, clone $old);

        $this->updateModel($request->header->account, $old, $new, InvestmentObjectAttributeValueL11nMapper::class, 'option_attribute_value_l11n', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Api method to delete InvestmentObjectAttributeValueL11n
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
    public function apiInvestmentObjectAttributeValueL11nDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeValueL11nDelete($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidDeleteResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11n $optionAttributeValueL11n */
        $optionAttributeValueL11n = InvestmentObjectAttributeValueL11nMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $this->deleteModel($request->header->account, $optionAttributeValueL11n, InvestmentObjectAttributeValueL11nMapper::class, 'option_attribute_value_l11n', $request->getOrigin());
        $this->createStandardDeleteResponse($request, $response, $optionAttributeValueL11n);
    }
}
