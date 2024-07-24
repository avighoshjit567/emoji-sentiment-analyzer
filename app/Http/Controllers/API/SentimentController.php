<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\SentimentAnalysis;
use Validator;
use App\Http\Resources\SentimentResource;
use Illuminate\Http\JsonResponse;

class SentimentController extends BaseController
{
    // Initiallize Emoji Score
    protected $emojiScores = [
        '😊' => 1,
        '😢' => -1,
        '❤' => 5,
        '😍' => 4,
        '😂' => 10,
        '😉' => 2,
        '😎' => 3,
        '😜' => 6,
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        $SentimentAnalysis = SentimentAnalysis::all();
        return $this->sendResponse(SentimentResource::collection($SentimentAnalysis), 'Analysis Result retrieved successfully.');
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function Store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'input_text' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $query = new SentimentAnalysis();
        $text = $request->input('input_text');
        $emojis = $this->extractEmojis($text);
        $sentimentScore = $this->calculateSentimentScore($emojis);
        $query->input_text = $text;
        $query->sentiment_score = $sentimentScore;
        $query->save();
        return $this->sendResponse(new SentimentResource($query), 'Analysis Added Successfully.');
    }

    // Function for extract emoji  
    public function extractEmojis($text)
    {
        preg_match_all('/[\x{1F600}-\x{1F64F}]/u', $text, $matches);
        return $matches[0];
    }

    // Function for calculate sentiment emoji score 
    public function calculateSentimentScore($emojis)
    {
        $score = 0;
        foreach ($emojis as $emoji) {
            $score += $this->emojiScores[$emoji] ?? 0;
        }
        return $score;
    }
}
