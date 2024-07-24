<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SentimentAnalysisController;

// Route::get('/', function () {
//     return view('welcome');
// });
// Routes for Emoji Sentiment Analyzer
Route::get('/', [SentimentAnalysisController::class, 'Index']);
Route::post('emoji_analyze', [SentimentAnalysisController::class, 'EmojiAnalyze'])->name('emoji.analyze');
Route::get('emoji_analyze_data', [SentimentAnalysisController::class, 'EmojiAnalyzeData'])->name('emoji.analyze.data');
Route::get('emoji_analyze_edit', [SentimentAnalysisController::class, 'EmojiAnalyzeEditData'])->name('emoji.analyze.edit');
