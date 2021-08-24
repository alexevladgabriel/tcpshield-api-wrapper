<?php

namespace TCPShield\API\Endpoints;

use stdClass;
use TCPShield\API\Adapter\Adapter;
use TCPShield\API\Adapter\ResponseException;
use TCPShield\API\Interfaces\BackendsInterface;
use TCPShield\API\Traits\BodyAccessorTrait;

class Backends implements BackendsInterface
{
    use BodyAccessorTrait;

    private $adapter;

    public $network;
    /**
     * BackendsInterface constructor.
     * @param Adapter $adapter
     * @param string $networkId
     */
    public function __construct(Adapter $adapter, string $networkId)
    {
        $this->adapter = $adapter;
        $this->network = $networkId;
    }

    /**
     * @return stdClass
     */
    public function listBackends(): stdClass
    {
        $backends = $this->adapter->get("networks/{$this->network}/backendSets");
        $this->body = json_decode($backends->getBody());

        return (object)[
            'result' => $this->body
        ];
    }

    /**
     * @param string $name
     * @param array $backends
     * @return bool
     */
    public function addBackend(
        string $name = '',
        array $backends = []
    ): bool {
        if(!empty($name)) {
            $options = [
                'name' => $name,
                'backends' => $backends,
            ];

            $backendSets = $this->adapter->post("networks/{$this->network}/backendSets", $options);
            $this->body = json_decode($backendSets->getBody());

            return true;
        }

        return false;
    }

    /**
     * @param string $backendId
     * @return stdClass
     */
    public function getBackendByID(string $backendId): stdClass
    {
        $backends = $this->adapter->get("networks/{$this->network}/backendSets/{$backendId}");
        $this->body = json_decode($backends->getBody());

        return (object)[
            'result' => $this->body,
        ];
    }

    /**
     * @param string $backendId
     * @param string $name
     * @param array $backends
     * @return bool
     */
    public function setBackend(
        string $backendId = '',
        string $name = '',
        array $backends = []
    ): bool {
        if(!empty($name) && !empty($backendId)) {
            $options = [
                'name' => $name,
                'backends' => $backends
            ];

            $backendSets = $this->adapter->patch("networks/{$this->network}/backendSets/{$backendId}", $options);
            $this->body = json_decode($backendSets->getBody());

            if($this->body->message == "Backend set updated successfully.")
                return true;
        }

        return false;
    }

    /**
     * @param string $backendId
     * @return bool
     */
    public function deleteBackend(string $backendId): bool
    {
        try {
            $backends = $this->adapter->delete("/networks/{$this->network}/backendSets/{$backendId}");
        } catch (ResponseException $exception) {
            return false;
        }

        $this->body = json_decode($backends->getBody());
        if($this->body->message == "Backend set deleted successfully.")
            return true;

        return false;
    }
}