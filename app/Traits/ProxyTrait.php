<?php
namespace App\Traits;

trait ProxyTrait {

    /**
     * @param int $id
     * @param array $proxies
     */
    public function syncProxies(int $id, array $proxies): void
    {
        $this->getById($id)->proxies()->sync($proxies);
    }
}
