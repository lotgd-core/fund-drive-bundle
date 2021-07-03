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

namespace Lotgd\Bundle\FundDrive\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class LotgdFundDriveExtension extends ConfigurableExtension
{
    public function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new PhpFileLoader($container, new FileLocator(\dirname(__DIR__).'/Resources/config'));

        $loader->load('services.php');

        /** @var \Lotgd\Bundle\FunDrive\Tool\Calculate */
        $tool = $container->get('lotgd_bundle.fund_drive.calculate_tool');

        $tool->setFundDriveProfit($mergedConfig['profit'])
            ->setFundDriveExpenses($mergedConfig['expenses'])
            ->setDeductFees($mergedConfig['deduct_fees'])
        ;

        /** @var \Lotgd\Bundle\FunDrive\Block\LotgdFunDriveBlock */
        $block = $container->get('lotgd_bundle.fund_drive.block.paypal');

        $block->setPaypalCurrency($mergedConfig['paypal_currency']);
    }
}
