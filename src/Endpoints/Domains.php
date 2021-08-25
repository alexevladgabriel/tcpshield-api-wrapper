<?php

namespace TCPShield\API\Endpoints;

use stdClass;
use TCPShield\API\Adapter\Adapter;
use TCPShield\API\Adapter\ResponseException;
use TCPShield\API\Interfaces\DomainsInterface;
use TCPShield\API\Traits\BodyAccessorTrait;

class Domains implements DomainsInterface
{
    use BodyAccessorTrait;

    private $adapter;

    private $network;

    public function __construct(
        Adapter $adapter,
        string $networkId
    ) {
        $this->adapter = $adapter;
        $this->network = $networkId;
    }

    /**
     * @GET Get Domains
     * @return stdClass
     */
    public function listDomains(): stdClass
    {
        $domains = $this->adapter->get("networks/{$this->network}/domains");
        $this->body = json_decode($domains->getBody());

        return (object)[
            'result' => $this->body
        ];
    }

    /**
     * @param string|null $name
     * @param int $backendSetId
     * @param bool $bac
     * @return stdClass
     */
    public function addDomain(
        string $name = '',
        int $backendSetId = -1,
        bool $bac = true
    ): stdClass {
        if($backendSetId != -1 && !empty($name)) {
            $options = [
                'name' => $name,
                'backend_set_id' => $backendSetId,
                'bac' => $bac
            ];

            $domain = $this->adapter->post("networks/{$this->network}/domains", $options);
            $this->body = json_decode($domain->getBody());

            return (object)$this->body;
        }

        return (object)[];
    }

    /**
     * @param string $domainId
     * @return stdClass
     */
    public function getDomainById(string $domainId): stdClass
    {
        $domain = $this->adapter->get("networks/{$this->network}/domains/{$domainId}");
        $this->body = json_decode($domain->getBody());

        return (object)[
            'result' => $this->body
        ];
    }

    /**
     * @param string $domainId
     * @param string $domainName
     * @param int $backendSetId
     * @param bool $bac
     * @return bool
     */
    public function setDomain(
        string $domainId,
        string $domainName = '',
        int $backendSetId = -1,
        bool $bac = true
    ): bool
    {
        if(!empty($domainName) && $backendSetId != -1) {
            $options = [
                'name' => $domainName,
                'backend_set_id' => $backendSetId,
                'bac' => $bac
            ];

            $domain = $this->adapter->patch("networks/{$this->network}/domains/{$domainId}", $options);
            $this->body = json_decode($domain->getBody());

            if($this->body->message == "")
                return true;
        }

        return false;
    }

    /**
     * @param string $domainId
     * @return bool
     */
    public function deleteDomain(string $domainId): bool
    {
        try {
            $domain = $this->adapter->delete("networks/{$this->network}/domains/{$domainId}");
        } catch (ResponseException $exception) {
            return false;
        }

        $this->body = json_decode($domain->getBody());
        if($this->body->message == "Domain deleted successfully.")
                return true;

        return false;
    }

    /**
     * @param string $domainName
     * @return bool
     */
    public function preVerifyDomain(string $domainName): bool
    {
        if(empty($domainName))
            return false;

        $domain = $this->adapter->post("networks/{$this->network}/domains/preverify", [ 'domain_name' => $domainName ]);
        $this->body = json_decode($domain->getBody());

        if($this->body->message == "Domain verified successfully")
            return true;

        return false;
    }

    /**
     * @param string $domainId
     * @return bool
     */
    public function verifyDomain(string $domainId): bool
    {
        if(empty($domainId))
            return false;

        $domain = $this->adapter->get("networks/{$this->network}/domains/{$domainId}/verify");
        $this->body = json_decode($domain->getBody());

        if($this->body->message == "Domain verified successfully.")
            return true;

        return false;
    }
}