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

namespace Lotgd\Bundle\FundDrive\Tool;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class Calculate
{
    private $cache;
    private $doctrine;
    private $fundDriveProfits;
    private $fundDriveExpenses;
    private $deductFees;

    public function __construct(CacheInterface $cache, EntityManagerInterface $doctrine)
    {
        $this->cache    = $cache;
        $this->doctrine = $doctrine;
    }

    public function progress()
    {
        return $this->cache->get('lotgd_bundle.fund_drive', function ($item) {
            $item->expiresAfter(900);

            $targetMonth = date('m');

            $start = date('Y').'-'.$targetMonth.'-01';
            $end   = date('Y-m-d', strtotime('+1 month', strtotime($start)));

            /** @var Lotgd\Core\Repository\PaylogRepository */
            $repository = $this->doctrine->getRepository('LotgdCore:Paylog');
            $query      = $repository->createQueryBuilder('u');

            $row = $query->select('sum(u.amount) AS gross', 'sum(u.txfee) AS fees')
                ->where('u.processdate >= :start AND u. processdate < :end')

                ->setParameter('start', $start)
                ->setParameter('end', $end)

                ->getQuery()
                ->getSingleResult()
            ;

            $goal = $this->fundDriveProfits + $this->fundDriveExpenses;

            $current = $row['gross'];

            if ($this->isDeductFees()) {
                $current -= $row['fees'];
            }

            $pct = ($current / $goal);

            return [
                'percent' => $pct,
                'goal'    => $goal,
                'current' => $current,
            ];
        });
    }

    public function setDeductFees(bool $deduct): self
    {
        $this->deductFees = $deduct;

        return $this;
    }

    public function isDeductFees(): bool
    {
        return $this->deductFees;
    }

    public function getFundDriveProfit(): int
    {
        return $this->fundDriveProfits;
    }

    public function setFundDriveProfit(int $profit): self
    {
        $this->fundDriveProfits = $profit;

        return $this;
    }

    public function getFundDriveExpenses(): int
    {
        return $this->fundDriveExpenses;
    }

    public function setFundDriveExpenses(int $expenses): self
    {
        $this->fundDriveExpenses = $expenses;

        return $this;
    }
}
