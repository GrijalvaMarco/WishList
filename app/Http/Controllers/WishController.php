<?php

namespace App\Http\Controllers;
Use App\Models\Wishes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class WishController extends Controller
{

    public function index(Request $request)
    {        
        $user = Auth::guard('api')->user();

        $whishes = Wishes::where('user_id', $user->id)->get();
        
        return response()->json([
            'status' => "success",
            'data' => $whishes
          ]);
    }

    public function show(Wishes $wish)
    {
        return $wish;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|string',
            'user_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $code = 422;
            $status = 'error';
            $response = $validator->messages();
        }else{
            $code = 200;
            $status = 'success';
            $response = Wishes::create($request->all());
        }

        return response()->json([
            'status' => $status,
            'data' => $response
          ],$code);
    }

    public function update(Request $request, Wishes $wish)
    {
        $wish->update($request->all());

        return response()->json($wish, 200);
    }

    public function delete(Wishes $wish)
    {
        $wish->delete();

        return response()->json(null, 204);
    }
}
