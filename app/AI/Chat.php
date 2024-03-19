<?php 
namespace App\AI;
use Illuminate\Support\Facades\Http;

class Chat{
    protected array $messages = [];

    public function messages(){
        return $this->messages;
    }

    public function systemMessage(string $message){
        $this->messages[] = [
            'role' => 'system',
            'content' => $message
        ];
        return $this;
    }
    public function replyMessage(string $message){
        return $this->send($message);
    }
    public function send(string $message){
        $this->messages[] = [
            'role' => 'user',
            'content' => $message
        ];
        // dd($this->messages);
        $response = Http::retry(3, 100)
        ->withToken(config('services.openai.api_key'))
        ->post('https://api.openai.com/v1/chat/completions',
            [
                "model"=> "gpt-3.5-turbo",
                "messages"=> $this->messages
            ]
        )->json('choices.0.message.content');
        if($response){
            $this->messages[] = [
                'role' => 'assistant',
                'content' => $response
            ];
            return $response;
        }

        
    }
}