<?php

namespace App\Http\Controllers;
use JWTAuth;	
use App\Http\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Models\User;

class ProfileController extends Controller
{
    public function store(Request $request)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        $user_id = $currentUser->id;

		$user = User::where('id', $user_id)->first();

		if (!$user)
		{
			return ["message" => "book owner dosent exist!"];
		}

        $profile = new Profile;
		$profile->user_id = $user_id;
		$profile->github_url = $request->github_url;
		$profile->twitter_url = $request->twitter_url;
		$profile->save();
		return json_encode($profile);

    }

    public function update(Request $request){
    	
        $currentUser = JWTAuth::parseToken()->authenticate();
        $user_id = $currentUser->id;
        $profile = Profile::where("user_id", $user_id);

/*		$user = User::where('id', $owner_id)->first();

		if (!$user)
		{
			return ["message" => "profile owner dosent exist!"];
		}*/

        if($profile){
			$profile->user_id = $user_id;
			$profile->github_url = $request->github_url;
			$profile->twitter_url = $request->twitter_url;
			$profile->save();

		    return json_encode($profile);
		}
		else
		{
			return ["message" => "profile not found"];
		}
  
    }
}
