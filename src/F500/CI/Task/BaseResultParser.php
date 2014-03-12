<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task;

/**
 * Class BaseResultParser
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task
 */
abstract class BaseResultParser implements ResultParser
{

    /**
     * @var string
     */
    protected $cn;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @param string $cn
     */
    public function __construct($cn)
    {
        $this->cn = $cn;
    }

    /**
     * @return string
     */
    public function getCn()
    {
        return $this->cn;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return array_replace_recursive(
            $this->getDefaultOptions(),
            $this->options
        );
    }

    /**
     * @return array
     */
    protected function getDefaultOptions()
    {
        return array();
    }
}
