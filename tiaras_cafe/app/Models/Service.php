<?php

namespace App\Models;

class Service
{
    private ?int $serviceId;
    private string $serviceName;
    private string $serviceDesc;

    public function __construct(
        string $serviceName,
        string $serviceDesc,
        ?int $serviceId = null
    ) {
        $this->serviceId = $serviceId;
        $this->serviceName = $serviceName;
        $this->serviceDesc = $serviceDesc;
    }

    public function getServiceId(): ?int { return $this->serviceId; }
    public function getServiceName(): string { return $this->serviceName; }
    public function getServiceDesc(): string { return $this->serviceDesc; }
}