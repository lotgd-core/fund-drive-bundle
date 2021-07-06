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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('lotgd_fund_drive');

        $treeBuilder->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->integerNode('expenses')
                    ->defaultValue(0)
                    ->info('Monthly cost of LoTGD Server. This adds up to the final goal.')
                ->end()
                ->integerNode('profit')
                    ->defaultValue(50)
                    ->info('Goal amount of profit')
                ->end()
                ->booleanNode('deduct_fees')
                    ->defaultTrue()
                    ->info('Deduct fees of PayPal from Goal')
                ->end()
                ->scalarNode('paypal_currency')
                    ->defaultValue('USD')
                    ->cannotBeEmpty()
                    ->info('Currency usage for PayPal USD, EUR ...')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
