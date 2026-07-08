<?php

use Twilio\Rest\Client;

class Twilio
{

    private $db;
    private $sid;
    private $token;
    private $number;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Send a WhatsApp message using Twilio API.
     *
     * @param string $client_number The recipient's phone number.
     * @param string $client_message The message content.
     * @return string|null The SID of the sent message or null on failure.
     */
    public function send(string $client_number, string $client_message): ?string
    {
        // Fetch Twilio info from database
        $this->db->query("SELECT * FROM app_twilio WHERE id = 1");
        $twilio_info = $this->db->single();

        // Validate configuration
        if (!$twilio_info || !isset($twilio_info->account_sid, $twilio_info->auth_token, $twilio_info->phone_number)) {
            error_log('Twilio configuration is missing or incomplete in app_twilio table.');
            return null;
        }

        $this->sid = $twilio_info->account_sid;
        $this->token = $twilio_info->auth_token;
        $this->number = 'whatsapp:+' . $twilio_info->phone_number;

        $recipient = 'whatsapp:+' . $client_number;

        try {
            // Initialize the Twilio client
            $client = new Client($this->sid, $this->token);

            // Send WhatsApp message
            $message = $client->messages->create(
                $recipient, // To
                [
                    'from' => $this->number, // From
                    'body' => $client_message
                ]
            );

            // Success response
            return $message->sid;
        } catch (Exception $e) {
            // Log the error for debugging
            error_log('Twilio Error: ' . $e->getMessage());
            return null;
        }
    }

    public function twilio_balance(){

        // Fetch Twilio info from database
        $this->db->query("SELECT * FROM app_twilio WHERE id = 1");
        $twilio_info = $this->db->single();

        // Validate configuration
        if (!$twilio_info || !isset($twilio_info->account_sid, $twilio_info->auth_token)) {
            error_log('Twilio configuration is missing or incomplete in app_twilio table.');
            return null;
        }

        $this->sid = $twilio_info->account_sid;
        $this->token = $twilio_info->auth_token;

        $client = new Client($this->sid, $this->token);

        // Fetch balance
        $balanceData = $client->api->v2010->balance->fetch();

        return $balanceData->balance . " " . $balanceData->currency;
    }
}
