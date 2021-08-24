<?php

namespace TCPShield\API\Endpoints;

use stdClass;
use TCPShield\API\Adapter\Adapter;
use TCPShield\API\Adapter\ResponseException;
use TCPShield\API\Interfaces\NetworksInterface;
use TCPShield\API\Traits\BodyAccessorTrait;

class Networks implements NetworksInterface
{
    use BodyAccessorTrait;

    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @GET Get Networks
     * @return stdClass
     */
    public function listNetworks() : stdClass
    {
        $user = $this->adapter->get('networks');
        $this->body = json_decode($user->getBody());

        return (object)[
            'result' => $this->body,
        ];
    }

    /**
     * @POST Create Network
     * @param string $name
     * @return stdClass
     */
    public function addNetwork(
        string $name = ''
    ): stdClass {
        $options = [];

        if(!empty($name))
            $options['name'] = $name;

        $network = $this->adapter->post('networks', $options);
        $this->body = json_decode($network->getBody());

        return (object)$this->body;
    }

    /**
     * @GET Get Network by ID
     * @param string $networkId
     * @return stdClass
     */
    public function getNetworkById(string $networkId): stdClass
    {
        $network = $this->adapter->get('networks/'.$networkId);
        $this->body = json_decode($network->getBody());

        return (object)[
            'result' => $this->body
        ];
    }

    /**
     * @PATCH Update the Network
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
    ) : bool
    {
        $options = [
            "connections_per_second_threshold" => $connectionPerSecondThreshold,
            "client_ban_seconds" => $clientBanSeconds,
            "client_allow_seconds" => $clientAllowSeconds,
            "mitigation_message" => $mitigation_message
        ];

        if(!empty($name))
            $options['name'] = $name;

        $network = $this->adapter->patch('networks/'.$networkId, $options);
        $this->body = json_decode($network->getBody());

        if($this->body->message == "Network updated successfully.")
            return true;

        return false;
    }

    /**
     * @DELETE Delete Network with ID
     * @param string $networkId
     * @return bool
     */
    public function deleteNetwork(string $networkId): bool
    {
        try {
            $network = $this->adapter->delete('networks/'.$networkId);
        } catch (ResponseException $exception) {
            return false;
        }

        $this->body = json_decode($network->getBody());
        if($this->body->message == "Network deleted successfully.")
            return true;

        return false;
    }
}