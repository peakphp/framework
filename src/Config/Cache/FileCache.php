<?php

declare(strict_types=1);

namespace Peak\Config\Cache;

use Peak\Config\Exception\CachePathNotFoundException;
use Peak\Config\Exception\CachePathNotWritableException;
use Peak\Config\Exception\CacheInvalidKeyException;
use Psr\SimpleCache\CacheInterface;

/**
 * Class FileCache
 * @package Peak\Config\Cache
 */
class FileCache implements CacheInterface
{
    /**
     * Cache absolute path
     * @var string
     */
    protected $path;

    /**
     * ConfigCache constructor.
     *
     * @param string $path
     * @throws CachePathNotFoundException
     * @throws CachePathNotWritableException
     */
    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new CachePathNotFoundException($path);
        } elseif (!is_writable($path)) {
            throw new CachePathNotWritableException($path);
        }
        $this->path = $path;
    }

    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function get($key, $default = null)
    {
        $this->checkKeyName($key);
        if ($this->isExpired($key)) {
            return $default;
        }

        return $this->getCacheFileContent($key);
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string                 $key   The key of the item to store.
     * @param mixed                  $value The value of the item to store, must be serializable.
     * @param null|int|\DateInterval $ttl   Optional. The TTL value of this item. If no value is sent and
     *                                      the driver supports TTL then the library may set a default value
     *                                      for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function set($key, $value, $ttl = null)
    {
        $this->checkKeyName($key);
        return $this->setCacheFileContent($key, $value, $ttl);
    }

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @return bool True if the item was successfully removed. False if there was an error.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function delete($key)
    {
        $this->checkKeyName($key);
        $filepath = $this->getCacheFilePath($key);

        if (!file_exists($filepath) || !@unlink($filepath)) {
            return false;
        }
        return true;
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear()
    {
        $result = true;
        $dir = new \DirectoryIterator($this->path);
        foreach ($dir as $file) {
            $extension = pathinfo($file->getFilename(), PATHINFO_EXTENSION);
            if (!$file->isDot() && $extension === 'ser') {
                if (!@unlink($this->path.'/'.$file->getFilename())) {
                    $result = false;
                }
            }
        }

        return $result;
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys    A list of keys that can obtained in a single operation.
     * @param mixed    $default Default value to return for keys that do not exist.
     *
     * @return iterable A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    public function getMultiple($keys, $default = null)
    {
        $final = [];
        foreach ($keys as $key) {
            $final[] = $this->get($key, $default);
        }
        return $final;
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable               $values A list of key => value pairs for a multiple-set operation.
     * @param null|int|\DateInterval $ttl    Optional. The TTL value of this item. If no value is sent and
     *                                       the driver supports TTL then the library may set a default value
     *                                       for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if $values is neither an array nor a Traversable,
     *   or if any of the $values are not a legal value.
     */
    public function setMultiple($values, $ttl = null)
    {
        $result = true;
        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $ttl)) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @return bool True if the items were successfully removed. False if there was an error.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    public function deleteMultiple($keys)
    {
        $result = true;
        foreach ($keys as $key) {
            if (!$this->delete($key)) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * Determines whether an item is present in the cache.
     *
     * NOTE: It is recommended that has() is only to be used for cache warming type purposes
     * and not to be used within your live applications operations for get/set, as this method
     * is subject to a race condition where your has() will return true and immediately after,
     * another script can remove it making the state of your app out of date.
     *
     * @param string $key The cache item key.
     *
     * @return bool
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function has($key)
    {
        $this->checkKeyName($key);
        $path = $this->getCacheFilePath($key);
        return file_exists($path);
    }

    /**
     * @param $key
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function isExpired($key)
    {
        $filepath = $this->getCacheFilePath($key);

        if (!file_exists($filepath) || fileatime($filepath) < time()) {
            return true;
        }

        return false;
    }

    /**
     * Check if key is a valid string name
     *
     * @param string $key
     * @throws CacheInvalidKeyException
     */
    protected function checkKeyName($key): void
    {
        if (!is_string($key) || preg_match("#[{}()/\\@:]#", $key)) {
            throw new CacheInvalidKeyException();
        }
    }

    /**
     * Generate a cache key complete file path
     *
     * @param string $key
     * @return string
     */
    protected function getCacheFilePath($key): string
    {
        return $this->path.'/'.sha1($key).'.ser';
    }

    /**
     * Get array from a file
     *
     * @param string $key
     * @return mixed
     */
    protected function getCacheFileContent($key)
    {
        $filepath = $this->getCacheFilePath($key);

        return unserialize(file_get_contents($filepath));
    }

    /**
     * Save cache key file content
     *
     * @param string $key
     * @param $content
     */
    protected function setCacheFileContent($key, $content, $ttl = null)
    {
        $result = true;
        $filepath = $this->getCacheFilePath($key);
        $content = serialize($content);
        if (file_put_contents($filepath, $content) === false) {
            $result = false;
        }

        if ($result !== false) {
            $ttl = $ttl + time();
            touch($filepath, $ttl);
        }

        return $result;
    }
}
