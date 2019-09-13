<?php

namespace App\Service\Destiny;

use App\Lib\Api\Destiny\ApiClientInterface;
use App\Lib\Api\Destiny\Domain\Profile\ProfileCollectibleData;
use App\Lib\Api\Destiny\Enum\Components;
use App\Lib\Api\Destiny\Enum\DestinyCollectibleState;
use App\Lib\Api\Destiny\Exception\ApiException;
use App\Lib\Api\Destiny\Exception\CollectiblesPrivacyException;
use App\Lib\Api\Destiny\Exception\NoPlayersFoundException;
use App\Service\Destiny\Exception\UserHaveItemException;
use App\Utils\BitmaskUtil;
use \Exception;

class UserHaveItemHandler
{
    private $client;


    public function __construct(ApiClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $nickname
     * @param string $itemId
     *
     * @return bool
     * @throws ApiException|UserHaveItemException|Exception
     */
    public function handle(string $nickname, string $itemId): bool
    {
        try {
            $player = $this->apiSearchOnePlayer($nickname);

            $collectibles = $this->client->getProfile($player['membershipType'], $player['membershipId'],
                [Components::COLLECTIBLES])->getData();

            return $this->collectionContainItem($collectibles, $itemId);
        } catch (CollectiblesPrivacyException|NoPlayersFoundException $exception) {
            throw new UserHaveItemException($nickname . ': ' . $exception->getMessage());
        }
    }


    /**
     * @param string $nickname
     * @param bool $throwErrorMoreOnePlayer
     * @return array
     * @throws ApiException|Exception
     */
    private function apiSearchOnePlayer(string $nickname): array
    {
        $response = $this->client->searchPlayer($nickname)->getData();

        if (count($response) > 1) {
            throw new UserHaveItemException('Found more that one player: ' . $nickname);
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