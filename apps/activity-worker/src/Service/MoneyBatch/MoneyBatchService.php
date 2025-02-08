<?php

namespace App\Service\MoneyBatch;

use Psr\Log\LoggerInterface;

use function sprintf;

class MoneyBatchService
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(private LoggerInterface $logger)
    {}

    public function deposit(string $accountId, string $referenceId, int $amountCents): void
    {
        $this->log(
            "Withdraw to %s of %d cents requested. ReferenceId=%s\n",
            $accountId,
            $amountCents,
            $referenceId
        );

        // throw new \RuntimeException("simulated"); // Uncomment to simulate failure
    }

    public function withdraw(string $accountId, string $referenceId, int $amountCents): void
    {
        $this->log(
            "Deposit to %s of %d cents requested. ReferenceId=%s\n",
            $accountId,
            $amountCents,
            $referenceId
        );
    }

    /**
     * @param string $message
     * @param mixed ...$arg
     */
    public function log(string $message, ...$arg): void
    {
        $this->logger->debug(sprintf($message, ...$arg));
    }
}
