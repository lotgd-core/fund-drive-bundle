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

namespace Lotgd\Bundle\FundDrive\Service\Block;

use Sonata\BlockBundle\Model\Block;
use Sonata\BlockBundle\Event\BlockEvent;

class LotgdFundDriveService
{
    public function onBlock(BlockEvent $event)
    {
        $block = new Block();
        $block->setId(uniqid('', true));
        $block->setSettings($event->getSettings());
        $block->setType('lotgd_bundle.fund_drive.block.paypal');

        $event->addBlock($block);
    }
}
