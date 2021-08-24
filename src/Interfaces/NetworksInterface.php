<?php

namespace TCPShield\API\Interfaces;

use stdClass;
use TCPShield\API\Adapter\Adapter;

/**
 * Interface NetworksInterface
 * @package TCPShield\API\Interfaces
 */
interface NetworksInterface
{
    /**
     * NetworksInterface constructor.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter);

    /**
     * @return stdClass
     */
    public function listNetworks(): stdClass;

    /**
     * @param string $name
     * @return stdClass
     */
    public function addNetwork(string $name = ''): stdClass;

    /**
     * @param string $networkId
     * @return stdClass
     */
    public function getNetworkById(string $networkId): stdClass;

    /**
     * @param string $networkId
     * @param string $name
     * @param int $connectionPerSecondThreshold
     * @param int $clientBanSeconds
     * @param int $clientAllowSeconds
     * @param string $mitigation_message
     * @return bool
     */
    public function setNetwork(
        string $networkId,
        string $name = '',
        int $connectionPerSecondThreshold = 0,
        int $clientBanSeconds = 0,
        int $clientAllowSeconds = 0,
        string $mitigation_message = "Message"
    ): bool;

    /**
     * @param string $networkId
     * @return bool
     */
    public function deleteNetwork(string $networkId): bool;
}