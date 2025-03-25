<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $prompt = $request->input('message');

        return response()->stream(function () use ($prompt) {
            $client = new \GuzzleHttp\Client();

            echo $prompt;

            try {
                $response = $client->post('https://api.openai.com/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'model' => 'gpt-4',
                        'messages' => [['role' => 'user', 'content' => $prompt]],
                        'stream' => true
                    ],
                    'stream' => true
                ]);

                $body = $response->getBody();
                // Process the stream line by line
                while (!$body->eof()) {
                    $line = trim($body->read(1024));

                    // Handle multiple chunks that might be received together
                    $lines = explode("\n", $line);
                    foreach ($lines as $singleLine) {
                        if (empty($singleLine)) continue;

                        // Lines from OpenAI stream always start with "data: "
                        if (strpos($singleLine, 'data: ') === 0) {
                            $data = substr($singleLine, 6); // Remove "data: " prefix

                            // Check for the end of the stream
                            if ($data === "[DONE]") {
                                break;
                            }

                            // Parse the JSON response
                            $json = json_decode($data, true);
                            if (json_last_error() === JSON_ERROR_NONE && isset($json['choices'][0]['delta']['content'])) {
                                $content = $json['choices'][0]['delta']['content'];
                                echo $content;
                                ob_flush();
                                flush();
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                echo json_encode(['error' => $e->getMessage()]);
                ob_flush();
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no'
        ]);
    }
}
