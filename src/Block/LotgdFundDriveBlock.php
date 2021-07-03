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

namespace Lotgd\Bundle\FundDrive\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\HttpFoundation\Response;
use Lotgd\Bundle\FundDrive\Tool\Calculate;

final class LotgdFundDriveBlock extends AbstractBlockService
{
    private $calculate;
    private $paypalCurrency;

    public function execute(BlockContextInterface $blockContext, ?Response $response = null): Response
    {
        $prog = $this->calculate->progress();

        return $this->renderResponse('@LotgdFundDrive/fund_drive.html.twig', [
            'settings'    => $blockContext->getSettings(),
            'block'       => $blockContext->getBlock(),
            'translation_domain' => 'bundle_fund_drive',
            'percent'        => $prog['percent'],
            'goal'           => $prog['goal'],
            'current'        => $prog['current'],
            'paypal_currency' => $this->paypalCurrency,

            'useText'        => get_module_setting('usetext'),
            'simbolPosition' => get_module_setting('simbolPosition'),
            'simbol'         => get_module_setting('simbol'),
            'useBar'         => get_module_setting('usebar'),
            'showDollars'    => get_module_setting('showdollars'),
        ], $response)->setTtl(900);
    }

    public function setCalculate(Calculate $calculate): self
    {
        $this->calculate = $calculate;

        return $this;
    }

    public function setPaypalCurrency(string $currency): self
    {
        $this->paypalCurrency = $currency;

        return $this;
    }
}
