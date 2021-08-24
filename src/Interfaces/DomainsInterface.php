<?php

namespace TCPShield\API\Interfaces;

use stdClass;
use TCPShield\API\Adapter\Adapter;

/**
 * Interface DomainsInterface
 * @package TCPShield\API\Interfaces
 */
interface DomainsInterface
{
    /**
     * DomainsInterface constructor.
     * @param Adapter $adapter
     * @param string $networkId
     */
    public function __construct(Adapter $adapter, string $networkId);

    /**
     * @return stdClass
     */
    public function listDomains(): stdClass;

    /**
     * @param string $name
     * @param int $backendSetId
     * @param bool $bac
     * @return stdClass
     */
    public function addDomain(string $name = '', int $backendSetId = -1, bool $bac = true): stdClass;

    /**
     * @param string $domainId
     * @return stdClass
     */
    public function getDomainById(string $domainId): stdClass;

    /**
     * @param string $domainId
     * @param string $domainName
     * @param int $backendSetId
     * @param bool $bac
     * @return bool
     */
    public function setDomain(string $domainId, string $domainName = '', int $backendSetId = -1, bool $bac = true): bool;

    /**
     * @param string $domainId
     * @return bool
     */
    public function deleteDomain(string $domainId): bool;

    /**
     * @param string $domainName
     * @return bool
     */
    public function preVerifyDomain(string $domainName): bool;

    /**
     * @param string $domainId
     * @return bool
     */
    public function verifyDomain(string $domainId): bool;
}