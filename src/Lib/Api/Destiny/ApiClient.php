<?php

namespace App\Lib\Api\Destiny;

use App\Lib\Api\Destiny\Creator\ResponseCreator;
use App\Lib\Api\Destiny\Creator\RequestCreator;
use App\Lib\Api\Destiny\Creator\RequestCreatorInterface;
use App\Lib\Api\Destiny\Creator\ResponseCreatorInterface;
use App\Lib\Api\Destiny\Handler\HandlerException;
use App\Lib\Api\Destiny\Handler\Request\AuthorizationToken;
use App\Lib\Api\Destiny\Handler\Request\FormRequestParameters;
use App\Lib\Api\Destiny\Handler\Request\RequestHandlerManager;
use App\Lib\Api\Destiny\Handler\Response\Basic\CheckHttpStatus;
use App\Lib\Api\Destiny\Handler\Response\Basic\JsonSerializer;
use App\Lib\Api\Destiny\Handler\Response\DestinyResponseHandler;
use App\Lib\Api\Destiny\Handler\Response\ResponseHandlerManager;
use App\Lib\Api\Destiny\Method\GetProfile;
use App\Lib\Api\Destiny\Method\MethodInterface;
use App\Lib\Api\Destiny\Method\SearchPlayer;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\RequestManagerInterface;

class ApiClient implements ApiClientInterface
{
    /**
     * @var RequestManagerInterface
     */
    private $requestManager;
    /**
     * @var RequestCreatorInterface
     */
    private $requestCreator;
    /**
     * @var ResponseCreatorInterface
     */
    private $responseCreator;


    public function __construct(RequestManagerInterface $manager, string $apiKey)
    {
        $this->initializeRequestCreator($apiKey);
        $this->initializeResponseCreator();
        $this->requestManager = $manager;
    }


    /**
     * @param string $membershipType
     * @param string $membershipId
     * @param array $components
     * @return ApiResponseInterface
     * @throws HandlerException
     */
    public function getProfile(string $membershipType, string $membershipId, array $components): ApiResponseInterface
    {
        $getProfile = new GetProfile($membershipType, $membershipId, $components);
        return $this->execute($getProfile);
    }


    /**
     * @param string $nickname
     * @return ApiResponseInterface
     * @throws HandlerException
     */
    public function searchPlayer(string $nickname): ApiResponseInterface
    {
        $searchPlayer = new SearchPlayer($nickname);
        return $this->execute($searchPlayer);
    }


    protected function initializeRequestCreator(string $apiKey): void
    {
        $handlerManager = new RequestHandlerManager([
            new AuthorizationToken($apiKey),
            new FormRequestParameters(),
        ]);

        $this->requestCreator = new RequestCreator($handlerManager);
    }


    protected function initializeResponseCreator(): void
    {
        $handlerManager = new ResponseHandlerManager([
            new CheckHttpStatus(),
            new JsonSerializer(),
            new DestinyResponseHandler()
        ]);

        $this->responseCreator = new ResponseCreator($handlerManager);
    }

    /**
     * @param MethodInterface $request
     * @return ApiResponseInterface
     *
     * @throws HandlerException
     */
    protected function execute(MethodInterface $request): ApiResponseInterface
    {
        // form request data from method
        $requestData = $this->requestCreator->create($request, $request->getRequestHandlers());

        // send request in request manager to server
        $response = $this->requestManager->execute($requestData);

        // set response handlers (validation, http status, json serializer)
        $this->responseCreator->setHandlers($request->getResponseHandlers());

        // return response
        return $this->responseCreator->create($response);
    }
}