<?php


namespace App\Lib\Api\Destiny\Handler\Response;


use App\Lib\Api\Destiny\Enum\Components;
use App\Lib\Api\Destiny\Exception\ApiException;
use App\Lib\Api\Destiny\Exception\CollectiblesPrivacyException;
use App\Lib\Api\Destiny\Exception\SerializeException;
use App\Lib\Api\Destiny\Response\ApiResponseEditInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\Response\ResponseDataInterface;

class CheckCollectiblesPrivacy extends BasicResponseHandler
{
    /**
     * @var array
     */
    private $components;

    public function __construct(array $apiParamComponents)
    {
        $this->components = $apiParamComponents;
    }

    /**
     * @param ResponseDataInterface $response
     * @param ApiResponseEditInterface $data
     *
     * @return ApiResponseInterface
     * @throws ApiException
     *
     */
    public function handle(ResponseDataInterface $response, ApiResponseEditInterface $data): ApiResponseInterface
    {
        // check collectibles privacy
        if (in_array(Components::COLLECTIBLES, $this->components)) {
            $collections = $data->getData();
            $this->verifyData($collections);
            if ($this->isPrivacy($collections)) {
                throw new CollectiblesPrivacyException('Player has private data');
            }
        }
        return $this->next($response, $data);
    }


    /**
     * @param array $data
     * @throws ApiException
     */
    private function verifyData(array $data): void
    {
        if (!array_key_exists('profileCollectibles', $data) || !array_key_exists('characterCollectibles', $data)) {
            throw new SerializeException('Key profileCollectibles or characterCollectibles not exists');
        }
    }


    private function isPrivacy(array $collections): bool
    {
        $profileCollection = $collections['profileCollectibles'];
        $charactersCollections = $collections['characterCollectibles'];
        return (!array_key_exists('data', $profileCollection) && !array_key_exists('data', $charactersCollections));
    }
}