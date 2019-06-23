<?php

namespace App\Service;

use Doctrine\DBAL\Connection;
use Exception;

class DatabaseChecker
{
    const EXCEPTION_MESSAGE_WHEN_READY = "An exception occurred in driver: SQLSTATE[HY000] [1049] Unknown database 'app'";

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function isDatabaseReady()
    {
        try {
            $this->connection->query();
        } catch (Exception $exception) {
            return $exception->getMessage() === self::EXCEPTION_MESSAGE_WHEN_READY;
        }
        return false;
    }
}
