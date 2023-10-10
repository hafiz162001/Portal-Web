<?php

namespace App\Http\Controllers\UserBundle;

use App\Http\Controllers\Controller;
use App\Models\AclMenu;
use Illuminate\Http\Request;
use Auth;

class AccessUserRoleController extends Controller
{
    public function remove(Request $request)
    {
        $data =$request->all();
        $result = \App\Models\AclMenu::where('user_id',$data['user_id'])
        ->where('menu_id',$data['menu_id'])
        ->where('sub_menu_id',$data['sub_menu_id'])
        ->delete();
        $response = false;
        if($result==1){
            $response = true;
        }
        return response()->json($response);
    }

    public function add(Request $request)
    {   
        $data =  $request->all();
        $model = new AclMenu();
        $model->user_id = $data['user_id']; 
        $model->menu_id = $data['menu_id']; 
        $model->sub_menu_id = $data['sub_menu_id']; 
            $response['data'] = 0;
            $response['result'] = false;
        if($model->save()){
            $response['data'] = $model;
            $response['data']['menu'] = $model->menu;
            $response['data']['submenu'] = $model->submenu;
            $response['result'] = true;
        }
        return response()->json($response);

    }

}
