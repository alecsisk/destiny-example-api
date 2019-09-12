<?php


namespace App\Lib\Api\Destiny\Handler\Response;


use App\Lib\Api\Destiny\Enum\Components;
use App\Lib\Api\Destiny\Handler\HandlerException;
use App\Lib\Api\Destiny\Handler\Response\BasicResponseHandler;
use App\Lib\Api\Destiny\Response\ApiResponseEditInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\Response\ResponseDataInterface;

class CheckCollectiblesPrivacy extends BasicResponseHandler
{
    /**
     * @var array
     */
    private $components;

    public const CODE_UNSERIALIZE_ERROR = 1;
    public const CODE_PRIVACY_ERROR = 2;

    public function __construct(array $apiParamComponents)
    {
        $this->components = $apiParamComponents;
    }

    /**
     * @param ResponseDataInterface $response
     * @param ApiResponseEditInterface $data
     *
     * @return ApiResponseInterface
     * @throws HandlerException
     *
     */
    public function handle(ResponseDataInterface $response, ApiResponseEditInterface $data): ApiResponseInterface
    {
        // check collectibles privacy
        if (in_array(Components::COLLECTIBLES, $this->components)) {
            $collections = $data->getData();
            $this->verifyData($collections);
            if ($this->isPrivacy($collections)) {
                throw new HandlerException('data is privacy', self::class, self::CODE_PRIVACY_ERROR);
            }
        }
        return $this->next($response, $data);
    }


    /**
     * @param array $data
     * @throws HandlerException
     */
    private function verifyData(array $data): void
    {
        if (!array_key_exists('profileCollectibles', $data) || !array_key_exists('characterCollectibles', $data)) {
            throw new HandlerException('Cant unserialize collection data', self::class, self::CODE_UNSERIALIZE_ERROR);
        }
    }


    private function isPrivacy(array $collections): bool
    {
        $profileCollection = $collections['profileCollectibles'];
        $charactersCollections = $collections['characterCollectibles'];
        return (!array_key_exists('data', $profileCollection) && !array_key_exists('data', $charactersCollections));
    }
}