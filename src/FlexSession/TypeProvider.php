<?php

namespace App\FlexSession;

use FlexSession\TypeProvider\TypeProviderInterface;
use Psr\Container\ContainerInterface;

/**
 * Class TypeProvider
 * @author Aleksandr Arofikin <sashaaro@gmail.com>
 */
class TypeProvider implements TypeProviderInterface
{
    /** @var ContainerInterface */
    private $container;

    const PERSIST_TYPE_CACHE_KEY = 'flex_session_type';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function changeType($type) {
        $cache = $this->container->get('cache.app');
        $item = $cache->getItem(self::PERSIST_TYPE_CACHE_KEY);
        $item->set($type);
        $cache->save($item);

        return $cache;
    }

    public function provide(): array
    {
        $cache = $this->container->get('cache.app');
        $item = $cache->getItem(self::PERSIST_TYPE_CACHE_KEY);

        $cacheDir = $this->container->get('kernel')->getCacheDir();

        if ($item->get() === 'pdo') {
            return [
                'type' => 'pdo',
                'dsn' => "sqlite:$cacheDir/session.sq3"
            ];
        }

        return [
            'type' => 'file',
            'path' => "$cacheDir/session"
        ];
    }
}