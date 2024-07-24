<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\SentimentAnalysis;

class SentimentAnalysisController extends Controller
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


    // Function for index page
    public function Index()
    {
        return view('index');
    }

    // Function for analyze emoji
    public function EmojiAnalyze(Request $request)
    {
        if ($request->has('delete')) {
            $query = SentimentAnalysis::find($request->delete);
            $query->delete();
            $message = 'Deleted Successfully!';
        }else{
            $request->validate([
                'text_input' => 'required'
            ]);
            $message = 'Added Successfully';
            if ($request->has('hidden_id')) {
                $message = 'Updated Successfully';
                $query = SentimentAnalysis::find($request->hidden_id);
            }else{
                $query = new SentimentAnalysis();
            }
            $text = $request->input('text_input');
            $emojis = $this->extractEmojis($text);
            $sentimentScore = $this->calculateSentimentScore($emojis);

            $query->input_text = $text;
            $query->sentiment_score = $sentimentScore;
            $query->save();
        }
        
        
        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);
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

    // Function for display sentiment emoji score in datatable
    public function EmojiAnalyzeData(Request $request)
    {
        
        $SentimentAnalysis = SentimentAnalysis::orderBy('id','desc');
        $this->i = 1;

        return DataTables::of($SentimentAnalysis)
        ->addColumn('id', function ($data) {
            return $this->i++;
        })
        ->addColumn('action', function ($data) {
            $htmlData = '';
            $htmlData .= '<a href="javascript:void(0)" data-id="'.$data->id.'" class="btn btn-info btn-sm tableEdit"><i class="fa fa-edit"></i></a>&nbsp;';
            $htmlData .= '<a href="javascript:void(0)" data-id="'.$data->id.'" class="btn btn-danger btn-sm tableDelete"><i class="fa fa-trash"></i></a>';
            return $htmlData;
        })
        ->rawColumns(['action'])
        ->toJson();
    }

    // Function for edit sentiment emoji score 
    public function EmojiAnalyzeEditData(Request $request)
    {
        $query = SentimentAnalysis::find($request->id);
        if (!$query) {
            return response()->json([
            'status' => 'error',
            'message' => 'Not Found, Please Try Again...',
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'data' => $query,
        ]);
    }
}
