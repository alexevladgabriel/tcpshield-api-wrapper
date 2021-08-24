<?php

namespace TCPShield\API\Interfaces;

use stdClass;
use TCPShield\API\Adapter\Adapter;

/**
 * Interface BackendsInterface
 * @package TCPShield\API\Interfaces
 */
interface BackendsInterface
{
    /**
     * BackendsInterface constructor.
     * @param Adapter $adapter
     * @param string $networkId
     */
    public function __construct(Adapter $adapter, string $networkId);

    /**
     * @return stdClass
     */
    public function listBackends(): stdClass;

    /**
     * @param string $name
     * @param array $backends
     * @return bool
     */
    public function addBackend(string $name, array $backends): bool;

    /**
     * @param string $backendId
     * @return stdClass
     */
    public function getBackendByID(string $backendId): stdClass;

    /**
     * @param string $backendId
     * @param string $name
     * @param array $backends
     * @return bool
     */
    public function setBackend(string $backendId, string $name, array $backends): bool;

    /**
     * @param string $backendId
     * @return bool
     */
    public function deleteBackend(string $backendId): bool;
}