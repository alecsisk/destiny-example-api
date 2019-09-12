<?php

namespace App\Lib\Api\Destiny\Handler\Request;

use App\Lib\Api\Destiny\Method\MethodDataInterface;
use App\Lib\Http\Request\Data\RequestDataEditInterface;
use App\Lib\Http\Request\Data\RequestDataInterface;
use App\Lib\Http\Request\Type\RequestTypeInterface;

class FormRequestParameters extends BasicRequestHandler
{

    /**
     * Dont forget call '$this->next()'
     * @throws \App\Lib\Api\Destiny\Handler\HandlerException
     */
    public function handle(MethodDataInterface $method, RequestDataEditInterface $request): RequestDataInterface
    {
        $url = $method->getBaseUrl();
        $parameters = $method->getParameters();

        $urlParameters = $this->searchUrlParameters($url, $parameters);
        $requestParameters = array_diff_key($parameters, $urlParameters);
        $url = $this->getRequestUrl($url, $urlParameters);

        // edit request
        $request->setUrl($url);
        $request = $this->addRequestParameters($request, $requestParameters);

        return $this->next($method, $request);
    }

    /**
     * for url: https://someurl.com/$var1/somestring/$var2
     * and parameters: ['$var1' => 'value', 'something' => 'data']
     * result is: ['$var1' => 'value']
     *
     * @param string $url
     * @param array $parameters
     * @return array
     */
    private function searchUrlParameters(string $url, array $parameters): array
    {
        return array_filter($parameters, function ($key) use ($url) {
            return strpos($url, $key) !== false;
        }, ARRAY_FILTER_USE_KEY);
    }

    private function getRequestUrl(string $url, array $parameters): string
    {
        array_walk($parameters, function (&$parameter, $key) {
            $parameter = urlencode($parameter);
        });
        return str_replace(array_keys($parameters), array_values($parameters), $url);
    }


    public function addRequestParameters(RequestDataEditInterface $request, array $parameters): RequestDataEditInterface
    {
        foreach ($parameters as $key => $value) {
            switch ($request->getRequestType()) {

                case RequestTypeInterface::METHOD_POST:
                    $request->addPostParameter($key, $value);
                    break;

                case RequestTypeInterface::METHOD_GET:
                    // in api documentation use param1=val1,val2 style
                    $request->addUrlParameter($key, implode(',', $value));
                    break;
            }
        }
        return $request;
    }
}