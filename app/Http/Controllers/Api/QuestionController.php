<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;

class QuestionController extends Controller
{
    public function store(StoreQuestionRequest $request)
    {
        $data = $request->validated();

        $question = Question::create($data);

        return response()->json(new QuestionResource($question), 201);
    }
}
