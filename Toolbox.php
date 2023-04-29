<?php
/**
 * @link https://www.github.com/javohirsd/toolbox
 * @author Javokhir Abdirasulov
 * @email  alienware7x@gmail.com
 * @date   29.04.2023
 */

namespace javohirsd\toolbox;

use Exception;

class Toolbox
{
    /**
     * Clear all characters except numbers.
     *
     * @param string $number
     * @return int|null
     */
    public static function toNumber(string $number): ?int
    {
        $result = preg_replace('/[^0-9]/', '', $number);
        return is_numeric($result) ? intval($result) : null;
    }


    /**
     * Format number to human-readable standard currency format
     *
     * @param int|string $number
     * @param string $currency
     * @return string
     */
    public static function toMoney(int|string $number, string $currency = ""): string
    {
        return number_format($number, 0, '.', ' ') . ($currency ? " ".$currency : $currency);
    }

    /**
     * @param int $length
     * @param bool $symbols
     * @return string
     * @throws Exception
     */
    public static function generateRandomString(int $length = 10, bool $symbols = false): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = $symbols ? $characters . "!@#$%^&*()[]{};:_-+=*<>?" : $characters;
        $charactersLength = strlen($characters);
        $randomString = "";
        for ($i = 0; $i < $length; $i++) {
            try {
                $randomString .= $characters[random_int(0, $charactersLength - 1)];
            } catch (Exception $e) {
                throw new Exception("Random string generation error: " . $e->getMessage());
            }
        }
        return $randomString;
    }


    /**
     * Send text or media message to telegram chat/group/channel using bot token.
     *
     * @param string $bot_token
     * @param string $method
     * @param array $data
     * @return bool|string
     */
    public static function sendTelegramMessage(string $bot_token, string $method, array $data): bool|string
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/' . $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING  => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT   => 1,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $data,
        ]);

        $response = curl_exec($curl);
        $result   = curl_errno($curl) === 0 && $response ? $response : curl_error($curl);

        curl_close($curl);

        return $result;
    }
}