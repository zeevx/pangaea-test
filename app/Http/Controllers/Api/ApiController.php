<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    /**
     *
     * @param Request $request
     * @param $topic
     * @return JsonResponse
     */
    public function publish(Request $request, $topic)
    {
        Log::info("===Publish Request===");
        Log::info((array)($request->all()));
        $data = $request->all();
        try {
            $this->notifySubscribers($topic, $data);
        }
        catch (\Exception $e){
            $response = response()->json([
                'success' => false,
                'message' => 'Error notifying subscribers'
            ]);
            Log::info("===Publish Response===");
            Log::info((object)$response);

            return $response;
        }

        $response =  response()->json([
            'success' => true,
            'message' => 'Published Successfully',
            'data' => $data
        ]);

        Log::info("===Publish Response===");
        Log::info((string)$response);

        return $response;

    }

    /**
     * @param $topic
     * @param $data
     * @return JsonResponse
     */
    public function notifySubscribers($topic, $data)
    {
        Log::info("===Notify Subscribers Request===");
        Log::info('===TOPIC===');
        Log::info((string)$topic);
        Log::info('===DATA===');
        Log::info((array)$data);

        $response = response()->json([
            'success' => true,
            'message' => 'Subscribers Notified Successfully',
            'topic' => $topic,
            'data' => $data
        ]);

        Log::info("===Notify Subscribers Response===");
        Log::info((string)$response);

        return $response;

    }

    public function createSubscription(Request $request, $topic)
    {
        Log::info("===Create Subscription Request===");
        Log::info((array)$request->all());

        $valid = Validator::make($request->all(), [
            'url' => 'required'
        ]);
        if ($valid->fails()){
            $response = response()->json([
                'success' => false,
                'message' => $valid->messages()->first()
            ]);
        }
        else{
            $response = response()->json([
                'success' => true,
                'message' => 'Subscription Successful',
                'url' => $request->url,
                'topic' => $topic
            ]);
        }
        Log::info("===Create Subscription Response===");
        Log::info((string)$response);
        return $response;
    }
}
