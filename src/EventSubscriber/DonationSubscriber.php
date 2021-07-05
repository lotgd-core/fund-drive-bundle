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

namespace Lotgd\Bundle\FundDrive\EventSubscriber;

use Lotgd\Core\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Cache\CacheInterface;

class DonationSubscriber implements EventSubscriberInterface
{
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function donation(): void
    {
        $this->cache->delete('lotgd_bundle.fund_drive');
    }

    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Events::PAYMENT_DONATION_SUCCESS => 'donation',
        ];
    }
}
