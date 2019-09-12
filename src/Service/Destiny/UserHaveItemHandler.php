<?php

namespace App\Service\Destiny;

use App\Lib\Api\Destiny\ApiClientInterface;
use App\Lib\Api\Destiny\Domain\Profile\ProfileCollectibleData;
use App\Lib\Api\Destiny\Enum\Components;
use App\Lib\Api\Destiny\Enum\DestinyCollectibleState;
use App\Lib\Api\Destiny\Exception\CollectiblesPrivacyException;
use App\Lib\Api\Destiny\Handler\HandlerException;
use App\Lib\Api\Destiny\Handler\Response\CheckCollectiblesPrivacy;
use App\Utils\BitmaskUtil;
use \Exception;

class UserHaveItemHandler
{
    private $client;
    private $nickname;
    private $itemId;


    public function __construct(ApiClientInterface $client)
    {
        $this->client = $client;
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

            $collectibles = $this->client->getProfile($player['membershipType'], $player['membershipId'],
                [Components::COLLECTIBLES])->getData();

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
     * @param string $nickname
     * @param bool $throwErrorMoreOnePlayer
     * @return array
     * @throws Exception
     */
    private function apiSearchOnePlayer(string $nickname): array
    {
        $response = $this->client->searchPlayer($nickname)->getData();
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