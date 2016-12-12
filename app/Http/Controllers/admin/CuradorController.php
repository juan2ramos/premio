<?php

namespace Theater\Http\Controllers\admin;

use Illuminate\Http\Request;

use Theater\Entities\Award;
use Theater\Http\Requests;
use Theater\Http\Controllers\Controller;

class CuradorController extends Controller
{
    function selectedUpdate(Request $request){

        $user = auth()->user();
        $region = $user->region->id;
        $limit = $region == 2 ? 6 : 3;
        $column = $user->role_id == 3 ? 'isSelected' : 'isPreselected';
        $state = $request->get('isSelected') == 'true' ? 1 : 0;

        $nAwards = Award::whereHas('user', function($query){
            $query->where('state', 1);
            })->whereRaw('award_type_id = 2 and ' . $column . ' = 1')
            ->whereHas('organization' , function($query) use($region){
                if($region > 1)
                    $query->whereHas('city', function($query) use($region){
                        $query->where('region_id', $region);
                    });
            })->count();

        if($nAwards >= $limit && $state == 1)
            return ['error' => true, 'quantity' => $limit];

        $award = Award::find($request->get('award_id'));
        $award->update(["$column" => $state]);
        return $award;
    }
}
