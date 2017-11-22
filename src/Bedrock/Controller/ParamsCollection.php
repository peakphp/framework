<?php

namespace Peak\Bedrock\Controller;

use Peak\Common\Collection;
use Peak\Validation\RuleBuilder;

class ParamsCollection extends Collection
{

    /**
     * Get an item by key
     *
     * @param  string $key
     * @return mixed
     */
    public function &__get($key)
    {
        if (!array_key_exists($key, $this->items)) {
            $real_key_index = $this->getRealKeyValueIndex($key);
            if ($real_key_index === false) {
                return $real_key_index;
            }
            return $this->items[$real_key_index];
        }
        return $this->items[$key];
    }

    /**
     * Use get() when key name is not possible to retrieve via magic method __get()
     * ex: params->get('my-page-id')
     * @param $key
     */
    public function get($key)
    {
        return $this->__get($key);
    }

    /**
     * Check if key exists. Same behavior as has()
     *
     * @param   string $key
     * @return  bool
     */
    public function __isset($key)
    {
        return $this->has($key);
    }

    /**
     * Has key with value
     * Can also add a rule to check is key value validate
     *
     * @param $key
     * @param RuleBuilder|null $rule
     * @return bool
     */
    public function has($key, RuleBuilder $rule = null)
    {
        if (array_key_exists($key, $this->items)) {
            $pass = true;
        } else {
            $real_key_index = $this->getRealKeyValueIndex($key);
            $pass = ($real_key_index === false) ?  false : true;
        }

        if (isset($rule) && $pass === true) {
            return $rule->validate(
                $this->items[$this->getRealKeyValueIndex($key)]
            );
        }

        return $pass;
    }

    /**
     * Get real key value index
     *
     * @param $key
     * @return int|null|string
     */
    protected function getRealKeyValueIndex($key)
    {
        foreach ($this->items as $index => $value) {
            if ($value === $key && isset($this->items[$index + 1])) {
                return $index + 1;
            }
        }
        return false;
    }

    /**
     * Contains specific value
     *
     * @param $value
     */
    public function contains($needle)
    {
        foreach ($this->items as $index => $value) {
            if ($value === $needle) {
                return true;
            }
        }
        return false;
    }
}
