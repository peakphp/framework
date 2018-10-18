<?php

declare(strict_types=1);

namespace Peak\Collection;

use Peak\Blueprint\Collection\Collection;

use \Exception;

class CollectionFlattener
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var array
     */
    protected $search = [];

    /**
     * @var string
     */
    protected $separator = '.';

    /**
     * Constructor
     *
     * @param Collection $coll
     */
    public function __construct(Collection $coll)
    {
        $this->collection = $coll;
    }

    /**
     * Change keys separators
     *
     * @param string $sep
     * @return $this
     * @throws Exception
     */
    public function separator(string $sep): CollectionFlattener
    {
        if (mb_strlen($sep) != 1 || $sep === '*') {
            throw new Exception(__CLASS__.': Separator must be 1 character and cannot be an asterisk (*)');
        }
        $this->separator = $sep;
        return $this;
    }

    /**
     * Simply flat all the collection
     *
     * @return array
     */
    public function flatAll(): array
    {
        $this->search = [];
        return $this->flatCollection($this->collection->toArray());
    }

    /**
     * Flat key
     *
     * @param mixed $prefix
     */
    public function flatKey($key): array
    {
        $this->search = [$key];
        return $this->flatCollection($this->collection->toArray());
    }

    /**
     * Flat multiple keys names
     *
     * @param mixed $prefix
     */
    public function flatKeys(array $keys): array
    {
        $this->search = $keys;
        return $this->flatCollection($this->collection->toArray());
    }

    /**
     * Flat collection recursively to one level key,val array
     *
     * @param array $data
     * @param string|null  $prefix
     * @param array $flat_data
     * @return array
     */
    protected function flatCollection(array $data, string $prefix = null, array $flat_data = []): array
    {
        foreach ($data as $key => $val) {
            if ($prefix !== null) {
                $key = $prefix.$this->separator.$key;
            }

            $skip_key = $this->skipKey($key);

            if (is_array($val)) {
                $flat_data = array_merge(
                    $flat_data,
                    $this->flatCollection($val, $key)
                );
                continue;
            }

            if ($skip_key) {
                continue;
            }

            $flat_data[$key] = $val;
        }

        return $flat_data;
    }

    /**
     * Detect if search is finishing by a wildcard (.*)
     *
     * @param string $search
     * @return bool
     */
    protected function hasWildCard(string $search): bool
    {
        return (substr($search, -2) === $this->separator.'*');
    }

    /**
     * Detect if key must be skipped according to $search
     *
     * @param string $key
     * @return bool
     */
    protected function skipKey(string $key): bool
    {
        if (!empty($this->search)) {
            foreach ($this->search as $search) {
                if ($this->hasWildCard($search)) {
                    $search = substr($search, 0,-2);
                    if (substr($key, 0, mb_strlen($search)) === $search) {
                        return false;
                    }
                } elseif ($search === $key) {
                    return false;
                }
            }
            return true;
        }

        return false;
    }
}
