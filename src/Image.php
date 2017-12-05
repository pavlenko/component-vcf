<?php

namespace PE\Component\VCF;

//TODO metadata
class Image
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $data;

    /**
     * @param string $url
     *
     * @return static
     */
    public static function fromUrl($url)
    {
        $image = new static();
        $image->url = $url;

        return $image;
    }

    /**
     * @param string $data
     *
     * @return static
     */
    public static function fromData($data)
    {
        $image = new static();
        $image->data = $data;

        return $image;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }
}