<?php

namespace QueryAuth\Request\Adapter\Incoming;

use QueryAuth\Request\IncomingRequestInterface;
use QueryAuth\Request\RequestInterface;
use \yii\web\Request;

/**
 * Incoming request adapter for Yii2
 */
class Yii2RequestAdapter implements IncomingRequestInterface, RequestInterface
{
    /**
     * @var Request request
     */
    protected $request;

    /**
     * @var bool
     */
    protected $onlyGet = false;

    /**
     * Public constructor
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethod()
    {
        return $this->request->getMethod();
    }

    /**
     * {@inheritDoc}
     */
    public function getHost()
    {
        return parse_url($this->request->getHostInfo())['host'];
    }

    /**
     * {@inheritDoc}
     */
    public function getPath()
    {
        return $this->request->getPathInfo();
    }

    /**
     * {@inheritDoc}
     */
    public function getParams()
    {
        if ($this->onlyGet) {
            return $this->request->get();
        }

        if (strtolower($this->getMethod()) === 'get') {
            return $this->request->get();
        }

        if (strtolower($this->getMethod()) === 'delete') {
            return array_merge($this->request->get(), $this->request->getBodyParams());
        }

        return $this->request->post();
    }

    /**
     * @param $onlyGet
     * @return $this
     * @throws \Exception
     */
    public function setOnlyGet($onlyGet)
    {
        if (!is_bool($onlyGet)) {
            throw new \Exception('Argument must be only boolean');
        }
        $this->onlyGet = $onlyGet;
        return $this;
    }
}
