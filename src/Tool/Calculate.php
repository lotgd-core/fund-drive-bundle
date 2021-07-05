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
    private $fund_drive_profits;
    private $fund_drive_expenses;
    private $deduct_fees;

    public function __construct(CacheInterface $cache, EntityManagerInterface $doctrine)
    {
        $this->cache = $cache;
        $this->doctrine = $doctrine;
    }

    public function progress()
    {
        return $this->cache->get('lotgd_bundle.fund_drive', function () {
            $targetMonth = \date('m');

            $start = \date('Y').'-'.$targetMonth.'-01';
            $end   = \date('Y-m-d', \strtotime('+1 month', \strtotime($start)));

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

            $goal = $this->fund_drive_profits + $this->fund_drive_expenses;

            $current = $row['gross'];

            if ($this->isDeductFees())
            {
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
        $this->deduct_fees = $deduct;

        return $this;
    }

    public function isDeductFees(): bool
    {
        return $this->deduct_fees;
    }

    public function getFundDriveProfit(): int
    {
        return $this->fund_drive_profits;
    }

    public function setFundDriveProfit(int $profit): self
    {
        $this->fund_drive_profits = $profit;

        return $this;
    }

    public function getFundDriveExpenses(): int
    {
        return $this->fund_drive_expenses;
    }

    public function setFundDriveExpenses(int $expenses): self
    {
        $this->fund_drive_expenses = $expenses;

        return $this;
    }
}
