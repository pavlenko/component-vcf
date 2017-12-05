<?php

namespace PE\Component\VCF;

class Collection
{
    /**
     * @var mixed[]
     */
    private $items;

    /**
     * @return mixed[]
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * @param int $index
     *
     * @return null|mixed
     */
    public function get($index)
    {
        return array_key_exists($index, $this->items) ? $this->items[$index] : null;
    }

    /**
     * @param int   $index
     * @param mixed $item
     *
     * @return $this
     */
    public function set($index, $item)
    {
        $this->items[$index] = $item;
        return $this;
    }

    /**
     * @param int $index
     *
     * @return bool
     */
    public function has($index)
    {
        return array_key_exists($index, $this->items);
    }

    /**
     * @param mixed $item
     *
     * @return $this
     */
    public function add($item)
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * @param int $index
     *
     * @return $this
     */
    public function remove($index)
    {
        unset($this->items[$index]);
        return $this;
    }
}