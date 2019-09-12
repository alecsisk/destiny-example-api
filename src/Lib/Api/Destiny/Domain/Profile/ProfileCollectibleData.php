<?php


namespace App\Lib\Api\Destiny\Domain\Profile;

use ErrorException;
use Exception;

class ProfileCollectibleData
{
    private $profileCollection;
    private $charactersCollections;

    /**
     * @param array $serialized
     * @throws Exception
     */
    public function unserializeFromArray(array $serialized): void
    {
        // data validator and save
        try {
            $this->profileCollection = $serialized['profileCollectibles']['data']['collectibles'];
            $charactersCollections = $serialized['characterCollectibles']['data'];

            foreach ($charactersCollections as $characterId => $characterCollection) {
                $collectionData = $characterCollection['collectibles'];
                $this->charactersCollections[$characterId] = $collectionData;
            }

        } catch (ErrorException $exception) {
            throw new Exception('Cant unserialize collection data: ' . $exception->getMessage());
        }
    }


    public function findItem(string $itemId): ?array
    {
        if ($profileResults = $this->findItemInProfile($itemId)) {
            $result['profile'] = $profileResults;
        }

        if ($charactersResults = $this->findItemInCharacters($itemId)) {
            $result['characters'] = $charactersResults;
        }

        return $result;
    }


    public function findItemInProfile(string $itemId): ?array
    {
        if (array_key_exists($itemId, $this->profileCollection)) {
            return $this->profileCollection[$itemId];
        }

        return null;
    }


    public function findItemInCharacters(string $itemId): ?array
    {
        $result = [];

        foreach ($this->charactersCollections as $characterId => $collection) {
            if (array_key_exists($itemId, $collection)) {
                $result[$characterId] = $collection[$itemId];
            }
        }

        return count($result) === 0 ? null : $result;
    }
}