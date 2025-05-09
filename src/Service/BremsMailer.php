<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class BremsMailer
{

    const WAYPOINT_URL = "https://live.waypointapi.com/v1/email_messages";
    const WAYPOINT_OTC_TEMPLATE_ID = "wptemplate_HnPJXA9ACT6wMKek";
    const WAYPOINT_VERIFICATION_EMAIL_TEMPLATE_ID = "wptemplate_U3AW5SB8DLZz6Zbf";

    public function __construct(private HttpClientInterface $client, private $waypoint_username, private $waypoint_password)
    {
    }

    public function sendVerificationEmail(string $to, $firstName, $verificationUrl)
    {
        return $this->sendMail($to, self::WAYPOINT_VERIFICATION_EMAIL_TEMPLATE_ID, ["display_name" => $firstName, "verification_url" => $verificationUrl]);
    }

    public function sendOtcMail(string $to, string $firstName, string $characters)
    {
        $arr = str_split($characters);

        return $this->sendMail($to, self::WAYPOINT_OTC_TEMPLATE_ID, variables: [
            "display_name" => $firstName,
            "char_1" => $arr[0],
            "char_2" => $arr[1],
            "char_3" => $arr[2],
            "char_4" => $arr[3],
            "char_5" => $arr[4],
            "char_6" => $arr[5],
            "chars" => $characters
        ]);
    }

    private function sendMail(string $to, string $templateId, array $variables): bool
    {
        $response = $this->client->request(
            'POST',
            self::WAYPOINT_URL,
            [
                'headers' => ['Content-Type' => 'application/json'],
                'auth_basic' => [$this->waypoint_username, $this->waypoint_password],
                'body' => json_encode([
                    "templateId" => $templateId,
                    "to" => $to,
                    "variables" => $variables,
                ])
            ]
        );

        if(!in_array($response->getStatusCode(), [200, 201, 204])) {
            return false;
        }

        return true;
    }

}
