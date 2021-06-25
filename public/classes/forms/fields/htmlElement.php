<?php

/**
 * Class HTMLElement
 * @property string $classPrefix prefix for the classes used by the element
 */
abstract class HTMLElement
{
    private string $classPrefix;

    /**
     * HTMLElement constructor.
     * @param string $classPrefix
     */
    public function __construct(string $classPrefix = "")
    {
        $this->classPrefix = $classPrefix;
    }

    /**
     * adds prefix to given class
     *
     * @param string $class
     * @return string prefixed-$class
     */
    protected function prefixClass(string $class): string
    {
        $class = strtolower($class);
        if ($this->classPrefix) {
            return "$this->classPrefix-$class";
        }
        return $class;
    }

    public function setClassPrefix(string $classPrefix): void
    {
        $this->classPrefix = $classPrefix;
    }

    /**
     * an HTML element must be able to be converted to HTML
     *
     * @return string HTML
     */
    public abstract function toHTML(): string;
}