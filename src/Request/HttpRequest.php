<?php
/**
 * Created by IntelliJ IDEA.
 * User: gurgen
 * Date: 2017-12-06
 * Time: 2:58 PM
 */

namespace Aragil\Request;

class HttpRequest extends Request
{
    private $queryParams;
    private $formParams;

    protected function init()
    {
        $this->queryParams = $_GET;
        $this->formParams = $_POST;
    }

    public function input($param = null, $default = null)
    {
        return $this->get($param, $default);
    }

    /**
     * @param mixed $param
     * @param mixed $default
     * @return mixed
     */
    public function get($param = null, $default = null)
    {
        if(is_null($param)) {
            return $this->all();
        }

        return $this->queryParams[$param] ?? $this->formParams[$param] ?? $default;
    }

    /**
     * @return array
     */
    public function all()
    {
        return array_merge(
            $this->formParams,
            $this->queryParams
        );
    }

    /**
     * @return array
     */
    protected function parsePathInfo()
    {
        $urlInfo = parse_url($_SERVER['REQUEST_URI']);
        return array_values(
            array_filter(
                explode('/', str_replace($_SERVER['SCRIPT_NAME'],'', $urlInfo['path'])),
                function ($i) {
                    return trim($i) !== '';
                }
            )
        );
    }

    /**
     * @return bool
     */
    public function isHttp()
    {
        return true;
    }
}