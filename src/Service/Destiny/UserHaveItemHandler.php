<?php

namespace App\Service\Destiny;

use App\Lib\Api\Destiny\Domain\Profile\ProfileCollectibleData;
use App\Lib\Api\Destiny\Enum\Components;
use App\Lib\Api\Destiny\Enum\DestinyCollectibleState;
use App\Lib\Api\Destiny\Exception\CollectiblesPrivacyException;
use App\Lib\Api\Destiny\Handler\HandlerException;
use App\Lib\Api\Destiny\Handler\Response\CheckCollectiblesPrivacy;
use App\Lib\Api\Destiny\Method\GetProfile;
use App\Lib\Api\Destiny\Method\SearchPlayer;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Service\Destiny\Api\DestinyApiService;
use App\Utils\BitmaskUtil;
use \Exception;

class UserHaveItemHandler
{
    private $api;
    private $nickname;
    private $itemId;


    public function __construct(DestinyApiService $api)
    {
        $this->api = $api;
    }

    /**
     * @param string $nickname
     * @param string $itemId
     *
     * @return bool
     * @throws Exception|CollectiblesPrivacyException
     */
    public function handle(string $nickname, string $itemId): bool
    {
        $this->nickname = $nickname;
        $this->itemId = $itemId;
        try {
            $player = $this->apiSearchOnePlayer($nickname);
            $collectibles = $this->apiGetProfile($player['membershipType'], $player['membershipId'],
                [Components::COLLECTIBLES]);
            return $this->collectionContainItem($collectibles, $itemId);
        } catch (Exception $exception) {
            $this->handlerExceptionProcessing($exception);
        }
        return false;
    }


    /**
     * @param Exception $exception
     * @throws Exception
     */
    private function handlerExceptionProcessing(HandlerException $exception): void
    {
        if ($exception instanceof HandlerException) {
            if ($exception->getUniqueName() === CheckCollectiblesPrivacy::class . CheckCollectiblesPrivacy::CODE_PRIVACY_ERROR) {
                throw new CollectiblesPrivacyException('User ' . $this->nickname . ' has private data');
            }
        }
        throw new Exception('Cant check user have item: ' . $exception->getMessage());
    }


    /**
     * @param string $membershipType
     * @param string $membershipId
     * @param array $components
     * @throws HandlerException
     */
    private function apiGetProfile(string $membershipType, string $membershipId, array $components)
    {
        $getProfile = new GetProfile($membershipType, $membershipId, $components);
        return $response = $this->api->execute($getProfile)->getData();
    }


    /**
     * @param string $nickname
     * @param bool $throwErrorMoreOnePlayer
     * @return array
     * @throws Exception
     */
    private function apiSearchOnePlayer(string $nickname): array
    {
        $response = $this->apiSearchPlayer($nickname)->getData();
        $responseCount = count($response);

        // TODO: в обработчики ответа?
        if ($responseCount === 0) {
            throw new Exception('No users found: ' . $nickname);
        }

        if (count($response) > 1) {
            throw new Exception('Found more that one player: ' . $nickname);
        }

        // first player data array
        return $response[0];
    }


    /**
     * @param string $nickname
     * @return ApiResponseInterface
     * @throws HandlerException
     */
    private function apiSearchPlayer(string $nickname): ApiResponseInterface
    {
        $searchPlayer = new SearchPlayer($nickname);
        return $this->api->execute($searchPlayer);
    }


    /**
     * @param array $data
     * @param string $itemId
     * @return bool
     * @throws Exception
     */
    private function collectionContainItem(array $data, string $itemId): bool
    {
        $collection = new ProfileCollectibleData();
        $collection->unserializeFromArray($data);
        $items = $collection->findItem($itemId);

        $notHaveItemCollectibleStates = [
            DestinyCollectibleState::NOT_ACQUIRED,
            DestinyCollectibleState::OBSCURED,
            DestinyCollectibleState::INVISIBLE,
            DestinyCollectibleState::CANNOT_AFFORD_MATERIAL_REQUIREMENTS,
            DestinyCollectibleState::PURCHASE_DISABLED
        ];

        foreach ($items as $typeOfData => $itemData) {
            if (BitmaskUtil::containsOneOfValues($itemData['state'], $notHaveItemCollectibleStates)) {
                return false;
            }
        }
        return true;
    }
}