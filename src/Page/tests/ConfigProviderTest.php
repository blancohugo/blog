<?php

namespace BlancoHugoTest\Blog\Page;

use BlancoHugo\Blog\Page\ConfigProvider;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $provider = new ConfigProvider();
        return $provider;
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function configCanBeRetrieved(ConfigProvider $provider)
    {
        $config = $provider();
        $this->assertNotEmpty($config);
        $this->assertIsArray($config);
    }
}
