<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\AclMenu;

class FestivalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            [
                'name' => "Festival",
                'code' => "festival",
                'status' => true,
                'nav_id' => 1,
                'icon' => "fas fa-tachometer-alt",
                'order' => 1
            ],
            [
                'name' => "Profile Management",
                'code' => "profile-management",
                'status' => true,
                'nav_id' => 2,
                'icon' => "fas fa-user",
                'order' => 100
            ],
        ];
        foreach ($menus as $menu) {
            $mn = new Menu();
            $mn->name = $menu["name"];
            $mn->code = $menu["code"];
            $mn->status = $menu["status"];
            $mn->nav_id = $menu["nav_id"];
            $mn->icon = $menu["icon"];
            $mn->order = $menu["order"];
            $mn->save();
        }

        $subMenus = [
            [
                'name' => "Festival",
                'code' => "festival",
                'status' => true,
                'menu_id' => 4,
                'path' => "festival.index",
            ],
            [
                'name' => "Festival Content",
                'code' => "festival-content",
                'status' => true,
                'menu_id' => 4,
                'path' => "festival-content.index",
            ],
            [
                'name' => "Festival Category",
                'code' => "festival-category",
                'status' => true,
                'menu_id' => 4,
                'path' => "festival-category.index",
            ],
            [
                'name' => "Festival Content Category",
                'code' => "festival-content-category",
                'status' => true,
                'menu_id' => 4,
                'path' => "festival-content-category.index",
            ],
            [
                'name' => "Festival Genre",
                'code' => "festival-genre",
                'status' => true,
                'menu_id' => 4,
                'path' => "festival-genre.index",
            ],
            [
                'name' => "Festival Content Like",
                'code' => "festival-content-like",
                'status' => true,
                'menu_id' => 4,
                'path' => "festival-content-like.index",
            ],
            [
                'name' => "Festival Content Comment",
                'code' => "festival-content-comment",
                'status' => true,
                'menu_id' => 4,
                'path' => "festival-content-comment.index",
            ],
            [
                'name' => "Profile",
                'code' => "profile",
                'status' => true,
                'menu_id' => 5,
                'path' => "profile.index",
            ],
            [
                'name' => "Festival Event",
                'code' => "festival-event",
                'status' => true,
                'menu_id' => 4,
                'path' => "festival-event.index",
            ],
            [
                'name' => "Festival Schedule",
                'code' => "festival-schedule",
                'status' => true,
                'menu_id' => 4,
                'path' => "festival-schedule.index",
            ],
            [
                'name' => "Location Detail",
                'code' => "location-detail",
                'status' => true,
                'menu_id' => 3,
                'path' => "location-detail.index",
            ],
            [
                'name' => "Seat",
                'code' => "seat",
                'status' => true,
                'menu_id' => 3,
                'path' => "seat.index",
            ],
        ];
        for ($i = 0; $i < 1; $i++) {
            foreach ($subMenus as $subMenu) {
                $sm = new SubMenu();
                $sm->name = $subMenu["name"];
                $sm->code = $subMenu["code"];
                $sm->status = $subMenu["status"];
                $sm->menu_id = $subMenu["menu_id"];
                $sm->path = $subMenu["path"];
                $sm->save();
            }
        }


        $aclMenus = [
            [
                "user_id" => 1,
                "menu_id" => 4,
                "sub_menu_id" => 26, //festival
            ],
            [
                "user_id" => 1,
                "menu_id" => 4, //festival-content
                "sub_menu_id" => 27,
            ],
            [
                "user_id" => 1,
                "menu_id" => 4,
                "sub_menu_id" => 28, //festival-category
            ],
            [
                "user_id" => 1,
                "menu_id" => 4,
                "sub_menu_id" => 29,  //festival-content-category
            ],
            [
                "user_id" => 1,
                "menu_id" => 4,
                "sub_menu_id" => 30, //festival-genre
            ],
            [
                "user_id" => 1,
                "menu_id" => 4,
                "sub_menu_id" => 31, //festival-content-like
            ],
            [
                "user_id" => 1,
                "menu_id" => 4,
                "sub_menu_id" => 32, //festival-content-comment
            ],
            [
                "user_id" => 1,
                "menu_id" => 5,
                "sub_menu_id" => 33, //profile
            ],
            [
                "user_id" => 1,
                "menu_id" => 4,
                "sub_menu_id" => 34, //festival-event
            ],
            [
                "user_id" => 1,
                "menu_id" => 4,
                "sub_menu_id" => 35, //festival-schedule
            ],
            [
                "user_id" => 1,
                "menu_id" => 3,
                "sub_menu_id" => 36, //location-detail
            ],
            [
                "user_id" => 1,
                "menu_id" => 3,
                "sub_menu_id" => 37, //seat
            ],
        ];
        for ($i = 1; $i < 3; $i++) {
            foreach ($aclMenus as $aclMenu) {
                $sm = new AclMenu();
                $sm->user_id = $i;
                $sm->menu_id = $aclMenu["menu_id"];
                $sm->sub_menu_id = $aclMenu["sub_menu_id"];
                $sm->save();
            }
        }
    }
}
