<?php

namespace Cajudev\Classes\Traits;

use Cajudev\Classes\Strings;
use Cajudev\Classes\Arrays;

trait ArrayAccessTrait
{
    public function offsetSet($key, $value)
    {
        $value = Arrays::isArray($value) ? new Arrays($value) : $value;

        if (Strings::isString($key)) {
            $path = (new Strings($key))->split('.');
            if ($path->count() > 1) {
                $key = $path[0];
                $this->content[$key] = new Arrays();
                $newPath = $path->shift()->join('.')->get();
                return $this->content[$key][$newPath] = $value;
            }
        }

        if ($key === null) {
            return $this->content[] = $value;
        }
        
        $this->content[$key] = $value;
    }

    public function offsetExists($key)
    {
        if (Strings::isString($key)) {
            $path = (new Strings($key))->split('.');

            if ($path->count() > 1) {
                $content = $this->getNewContent($path[0]);
                $key     = $this->getNewKey($path);
                return isset($content[$key]);
            }
        }

        return isset($this->content[$key]);
    }

    public function offsetUnset($key)
    {
        if (Strings::isString($key)) {
            $path = (new Strings($key))->split('.');

            if ($path->count() > 1) {
                $content = $this->getNewContent($path[0]);
                $key     = $this->getNewKey($path);
                unset($content[$key]);
                return;
            }
        }

        unset($this->content[$key]);
    }

    public function &offsetGet($key)
    {
        if (Strings::isString($key)) {
            $path = (new Strings($key))->split('.');

            if ($path->count() > 1) {
                $content = $this->getNewContent($path[0]);
                $key     = $this->getNewKey($path);
                return $content[$key];
            }
        }

        $ret =& $this->content[$key];
        $ret = Arrays::isArray($ret) ? new Arrays($ret) : $ret;
        return $ret;
    }

    public function getNewContent(string $key)
    {
        $content = $this->content[$key] ?? null;
        return Arrays::instanceOf($content) ? $content : new Arrays($content);
    }

    public function getNewKey(Arrays $path): string
    {
        return $path->shift()->join('.')->get();
    }
}