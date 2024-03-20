<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\InvestmentManagement\Admin
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\InvestmentManagement\Admin;

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Config\SettingsInterface;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Module\InstallerAbstract;
use phpOMS\Module\ModuleInfo;

/**
 * Installer class.
 *
 * @package Modules\InvestmentManagement\Admin
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Installer extends InstallerAbstract
{
    /**
     * Path of the file
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = __DIR__;

    /**
     * {@inheritdoc}
     */
    public static function install(ApplicationAbstract $app, ModuleInfo $info, SettingsInterface $cfgHandler) : void
    {
        parent::install($app, $info, $cfgHandler);

        /* Amount types */
        $fileContent = \file_get_contents(__DIR__ . '/Install/amounttypes.json');
        if ($fileContent === false) {
            return;
        }

        /** @var array $types */
        $types       = \json_decode($fileContent, true);
        $amountTypes = self::createAmountTypes($app, $types);
    }

    /**
     * Install amount type
     *
     * @param ApplicationAbstract $app   Application
     * @param array               $types Attribute definition
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createAmountTypes(ApplicationAbstract $app, array $types) : array
    {
        /** @var array<string, array> $amountTypes */
        $amountTypes = [];

        /** @var \Modules\InvestmentManagement\Controller\ApiController $module */
        $module = $app->moduleManager->get('InvestmentManagement', 'Api');

        /** @var array $type */
        foreach ($types as $type) {
            $response = new HttpResponse();
            $request  = new HttpRequest();

            $request->header->account = 1;
            $request->setData('name', $type['name'] ?? '');
            $request->setData('title', \reset($type['l11n']));
            $request->setData('language', \array_keys($type['l11n'])[0] ?? 'en');

            $module->apiAmountTypeCreate($request, $response);

            $responseData = $response->getData('');
            if (!\is_array($responseData)) {
                continue;
            }

            $amountTypes[$type['name']] = \is_array($responseData['response'])
                ? $responseData['response']
                : $responseData['response']->toArray();

            $isFirst = true;
            foreach ($type['l11n'] as $language => $l11n) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }

                $response = new HttpResponse();
                $request  = new HttpRequest();

                $request->header->account = 1;
                $request->setData('title', $l11n);
                $request->setData('language', $language);
                $request->setData('type', $amountTypes[$type['name']]['id']);

                $module->apiAmountTypeL11nCreate($request, $response);
            }
        }

        return $amountTypes;
    }
}
