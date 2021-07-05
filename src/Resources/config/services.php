<?php

/**
 * This file is part of "LoTGD Bundle Fund Drive".
 *
 * @see https://github.com/lotgd-core/bundle-fund-drive
 *
 * @license https://github.com/lotgd-core/bundle-fund-drive/blob/main/LICENSE
 * @author IDMarinas
 *
 * @since 0.1.0
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lotgd\Bundle\FundDrive\Block\LotgdFundDriveBlock;
use Lotgd\Bundle\FundDrive\Service\Block\LotgdFundDriveService;
use Lotgd\Bundle\FundDrive\Tool\Calculate;

return static function (ContainerConfigurator $container)
{
    $container->services()
        ->set('lotgd_bundle.fund_drive.calculate_tool', Calculate::class)
            ->args([
                new ReferenceConfigurator('cache.app'),
                new ReferenceConfigurator('doctrine.orm.entity_manager')
            ])

        //-- Block
        ->set('lotgd_bundle.fund_drive.block.paypal', LotgdFundDriveBlock::class)
            ->args([
                new ReferenceConfigurator('twig')
            ])
            ->call('setCalculate', [new ReferenceConfigurator('lotgd_bundle.fund_drive.calculate_tool')])
            ->tag('sonata.block')
        ->alias(LotgdFundDriveBlock::class, 'lotgd_bundle.fund_drive.block.paypal')

        ->set('lotgd_bundle.fund_drive.service.paypal', LotgdFundDriveService::class)
            ->tag('kernel.event_listener', ['event' => 'sonata.block.event.lotgd_core.paypal', 'method' => 'onBlock'])
    ;
};
