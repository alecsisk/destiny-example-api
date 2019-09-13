<?php


namespace App\Lib\Api\Destiny\Handler\Response;


use App\Lib\Api\Destiny\Exception\ApiException;
use App\Lib\Api\Destiny\Response\ApiResponseEditInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\Response\ResponseDataInterface;


class MultiplePlayerHandler extends BasicResponseHandler
{
    public const FIELD_DISPLAY_NAME = 'displayName';
    public const FIELD_MEMBERSHIP_ID = 'membershipId';


    private $displayName;
    /**
     * @var bool
     */
    private $useCaseCmp;

    public function __construct(string $displayName, bool $useCaseCmp = true)
    {
        $this->displayName = $displayName;
        $this->useCaseCmp = $useCaseCmp;
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
        $responseData = $data->getData();

        $handedIds = [];

        $result = array_filter($responseData, function ($userData) use (&$handedIds) {

            $currentDisplayName = $userData[self::FIELD_DISPLAY_NAME];
            $currentMembershipId = $userData[self::FIELD_MEMBERSHIP_ID];

            if ($this->isNeedleDisplayName($currentDisplayName) && !in_array($currentMembershipId, $handedIds)) {
                $handedIds[] = $currentMembershipId;
                return true;
            }

            return false;
        });

        $data->setData($result);

        return $this->next($response, $data);
    }


    private function isNeedleDisplayName(string $displayName): bool
    {
        return $this->useCaseCmp ? strcasecmp($this->displayName, $displayName) === 0 : strcmp($this->displayName,
                $displayName) === 0;
    }
}