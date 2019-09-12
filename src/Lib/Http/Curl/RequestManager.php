<?php

namespace App\Lib\Http\Curl;

use App\Lib\Http\Request\Data\RequestDataInterface;
use App\Lib\Http\Request\RequestFactoryInterface;
use App\Lib\Http\Request\RequestInterface;
use App\Lib\Http\RequestManagerInterface;
use App\Lib\Http\Response\ResponseDataInterface;

class RequestManager implements RequestManagerInterface
{
    private $factory;

    public function __construct(RequestFactoryInterface $factory)
    {
        $this->factory = $factory;
    }


    public function execute(RequestDataInterface $data): ResponseDataInterface
    {
        $request = $this->factory->create($data);

        $ch = curl_init();

        $this->prepareRequest($ch, $request);

        $response = new ResponseBuilder(curl_exec($ch), $ch);

        curl_close($ch);

        return $response;
    }


    private function prepareRequest(&$ch, RequestInterface $request):void
    {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->getRequestType());
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request->getHeaders() ?? []);
        curl_setopt($ch, CURLOPT_URL, $request->getUrl());
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($post = $request->getPostFields()) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
    }
}