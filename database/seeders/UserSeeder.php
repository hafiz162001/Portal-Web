<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Navigation;
use App\Models\UserRole;
use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\AclMenu;
use App\Models\Banner;
use App\Models\Gallery;
use App\Models\UserApps;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $beaconTypes = ["Mural", "Gate In", "Gate Out", "Area", "Toko"];
        // foreach ($beaconTypes as $key => $beaconType) {
        //     BeaconType::create([
        //         'name' => $beaconType
        //     ]);
        // }

        $beacons = [
            [
                'beacon_uid' => 'FF:50:53:D3:6B:60',
                'name' => 'GATE01',
                'beacon_type_id' => 2,
                'range' => 100
            ],
            [
                'beacon_uid' => 'FB:17:2A:FE:8C:A8',
                'name' => 'ART01',
                'beacon_type_id' => 1,
                'range' => 100
            ],
            [
                'beacon_uid' => 'DF:A2:C9:EB:AD:A9',
                'name' => 'ART02',
                'beacon_type_id' => 1,
                'range' => 100
            ],
            [
                'beacon_uid' => 'E9:E9:A4:AD:89:D1',
                'name' => 'MERCH01',
                'beacon_type_id' => 5,
                'range' => 100
            ],
            [
                'beacon_uid' => 'D8:A3:EC:E3:7C:EB',
                'name' => 'COMMON01',
                'beacon_type_id' => 4,
                'range' => 100
            ],
            [
                'beacon_uid' => 'D5:60:C9:67:6F:68',
                'name' => 'COMMON02',
                'beacon_type_id' => 4,
                'range' => 100
            ],
            [
                'beacon_uid' => 'D5:60:C9:67:6F:70',
                'name' => 'CHECKOUT01',
                'beacon_type_id' => 3,
                'range' => 100
            ],
            [
                'beacon_uid' => 'CB:0E:C7:37:CA:B6',
                'name' => 'MERCH02',
                'beacon_type_id' => 5,
                'range' => 100
            ],
            [
                'beacon_uid' => 'CE:44:36:A6:2A:D1',
                'name' => 'MERCH03',
                'beacon_type_id' => 5,
                'range' => 100
            ],
            [
                'beacon_uid' => 'DF:47:57:3E:51:DF',
                'name' => 'ART03',
                'beacon_type_id' => 1,
                'range' => 100
            ],
            [
                'beacon_uid' => 'EC:2F:32:E8:A9:66',
                'name' => 'GATE02',
                'beacon_type_id' => 2,
                'range' => 100
            ],
            [
                'beacon_uid' => 'EE:9C:B8:64:18:74',
                'name' => 'ART04',
                'beacon_type_id' => 1,
                'range' => 100
            ],
            [
                'beacon_uid' => 'D8:7F:6F:2B:41:EC',
                'name' => 'MERCH04',
                'beacon_type_id' => 5,
                'range' => 100
            ],
        ];

        // foreach ($beacons as $key => $beacon) {
        //     Beacon::create($beacon);
        // }

        $beaconArts = [
            [
                // 'beacon_id' => 2,
                'title' => 'Monalisa',
                'description' => 'Monalisa de caprio dolor sun umet'
            ],
            [
                // 'beacon_id' => 3,
                'title' => 'Soekarno',
                'description' => 'Soekarno de caprio dolor sun umet'
            ],
            [
                // 'beacon_id' => 10,
                'title' => 'SBY',
                'description' => 'Soekarno de caprio dolor sun umet'
            ],
        ];


        // foreach ($beaconArts as $key => $beaconArt) {
        //     $beacon = BeaconArt::create($beaconArt);

        //     $beaconIds = [2, 3, 10];
        //     $beaconRelation = [
        //         'parent' => 'beaconArt',
        //         'parent_id' => $beacon->id,
        //         'beacon_id' => $beaconIds[$key]
        //     ];
        //     BeaconRelation::create($beaconRelation);
        // }
        $productCategories = [
            [
                'name' => 'Makanan Utama',
                'type' => 'eat'
            ],
            [
                'name' => 'Makanan Ringan',
                'type' => 'eat'
            ],
            [
                'name' => 'T Shirt',
                'type' => 'shop'
            ],
            [
                'name' => 'Jeans',
                'type' => 'shop'
            ],
        ];
        // foreach ($productCategories as $key => $category) {
        //     ProductCategory::create($category);
        // }
        $blocLocation = [
            [
                'name' => 'M Bloc Space',
                'location_detail' => 'Jl. Panglima Polim No.37, RW.1, Melawai, Kec. Kby. Baru, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12160',
                'indonesia_village_id' => 25563
            ],
            [
                'name' => 'Pos Bloc Jakarta',
                'location_detail' => 'Jl. Pos No.2, Ps. Baru, Kecamatan Sawah Besar, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10710',
                'indonesia_village_id' => 25563
            ],
        ];
        // foreach ($blocLocation as $key => $bloc) {
        //     BlocLocation::create([
        //         'name' => $bloc["name"],
        //         'location_detail' => $bloc["location_detail"],
        //         'indonesia_village_id' => $bloc["indonesia_village_id"],
        //     ]);
        // }
        $userApps = UserApps::create([
            'phone' => '628998608056',
            'role' => 0,
            'bloc_location_id' => 1
        ]);

        // $beaconBlocs = [
        //     [
        //         'bloc_location_id' => 1,
        //         'beacon_id' => 1,
        //     ],
        //     [
        //         'bloc_location_id' => 1,
        //         'beacon_id' => 7,
        //     ],
        // ];
        // foreach ($beaconBlocs as $key => $beacon) {
        //     BeaconBloc::create($beacon);

        // }
        $beaconRelations = [
            [
                'parent' => 'blocLocation',
                'parent_id' => 1,
                'beacon_id' => 1
            ],
            [
                'parent' => 'blocLocation',
                'parent_id' => 1,
                'beacon_id' => 7
            ],
        ];
        // foreach ($beaconRelations as $key => $beaconRelation) {
        //     BeaconRelation::create($beaconRelation);
        // }
        $categoryLocation = [
            [
                'name' => 'Cafe',
                'type' => 'eat',
            ],
            [
                'name' => 'Restorant',
                'type' => 'eat',
            ],
            [
                'name' => 'Bar',
                'type' => 'eat',
            ],
        ];
        // foreach ($categoryLocation as $key => $cl) {
        //     CategoryLocation::create($cl);
        // }
        $locations = [
            [
                'name' => 'Titik Temu Coffee',
                'phone' => '628998608056',
                'description' => 'lorem ipsum',
                'time_from' => "16:00:00",
                'time_to' => "22:00:00",
                'type' => 'eat',
                'bloc_location_id' => 1,
                'category_location_id' => 1,
            ],
            [
                'name' => 'Kedai Tjikini',
                'phone' => '628998608055',
                'description' => 'lorem ipsum',
                'time_from' => "16:00:00",
                'time_to' => "22:00:00",
                'type' => 'eat',
                'bloc_location_id' => 1,
                'category_location_id' => 2
            ],
            [
                'name' => 'Twalen',
                'phone' => '628998608055',
                'description' => 'lorem ipsum',
                'time_from' => "16:00:00",
                'time_to' => "22:00:00",
                'type' => 'eat',
                'bloc_location_id' => 1,
                'category_location_id' => 1
            ],
            [
                'name' => 'Connectoon Store',
                'phone' => '628998608054',
                'description' => 'lorem ipsum',
                'time_from' => "16:00:00",
                'time_to' => "22:00:00",
                'type' => 'shop',
                'bloc_location_id' => 2,
                'category_location_id' => 1
            ],
            [
                'name' => 'Matalokal',
                'phone' => '628998608053',
                'description' => 'lorem ipsum',
                'time_from' => "16:00:00",
                'time_to' => "22:00:00",
                'type' => 'shop',
                'bloc_location_id' => 2,
                'category_location_id' => 1
            ],
        ];

        // foreach ($locations as $key => $loc) {
        //     $location = Location::create($loc);
        // }

        $beaconRelations = [
            [
                'parent' => 'location',
                'parent_id' => 1,
                'beacon_id' => 4
            ],
            [
                'parent' => 'location',
                'parent_id' => 2,
                'beacon_id' => 8
            ],
            [
                'parent' => 'location',
                'parent_id' => 3,
                'beacon_id' => 9
            ],
        ];
        // foreach ($beaconRelations as $key => $beaconRelation) {
        //     BeaconRelation::create($beaconRelation);
        // }

        $galleries = [
            [
                'image' => 'banner-2.png',
                'parent_id' => 1
            ],
            [
                'image' => 'banner-1.png',
                'parent_id' => 1
            ],
            [
                'image' => 'banner-2.png',
                'parent_id' => 2
            ],
            [
                'image' => 'banner-1.png',
                'parent_id' => 2
            ],
            [
                'image' => 'banner-2.png',
                'parent_id' => 3
            ],
            [
                'image' => 'banner-1.png',
                'parent_id' => 3
            ],
            [
                'image' => 'banner-2.png',
                'parent_id' => 4
            ],
            [
                'image' => 'banner-1.png',
                'parent_id' => 4
            ],
        ];
        foreach ($galleries as $key => $gal) {
            Gallery::create($gal);
        }

        // Promo::create([
        //     'title' => 'Pestapora 2022',
        //     'order' => 1,
        //     'date_from' => "2022-12-05",
        //     'date_to' => "2022-12-10",
        //     'image' => "banner-2.png",
        //     'period_of_use' => "banner-2.png",
        //     'usage' => 0,
        //     'description' => "Cupcake ipsum dolor sit amet. Powder dragée caramels jelly shortbread brownie pudding. Chocolate bar shortbread sesame snaps lemon drops tootsie roll. Bear claw caramels biscuit caramels macaroon tart. Pudding powder chupa chups oat cake fruitcake candy canes lemon drops gummies tart. Cake cotton candy chocolate cake macaroon chupa chups pie pastry muffin. Bear claw caramels cake jelly-o cheesecake danish sweet roll. Soufflé caramels muffin cookie chocolate bar.",
        //     'term_and_condition' => "Cupcake ipsum dolor sit amet. Powder dragée caramels jelly shortbread brownie pudding. Chocolate bar shortbread sesame snaps lemon drops tootsie roll. Bear claw caramels biscuit caramels macaroon tart. Pudding powder chupa chups oat cake fruitcake candy canes lemon drops gummies tart. Cake cotton candy chocolate cake macaroon chupa chups pie pastry muffin. Bear claw caramels cake jelly-o cheesecake danish sweet roll. Soufflé caramels muffin cookie chocolate bar.",
        // ]);
        // Event::create([
        //     'title' => 'Pestapora 2022',
        //     'order' => 1,
        //     'date_from' => "2022-12-05",
        //     'date_to' => "2022-12-10",
        //     'time_from' => "16:00:00",
        //     'time_to' => "22:00:00",
        //     'indonesia_village_id' => 25526,
        //     'image' => "banner-2.png",
        //     'description' => "Cupcake ipsum dolor sit amet. Powder dragée caramels jelly shortbread brownie pudding. Chocolate bar shortbread sesame snaps lemon drops tootsie roll. Bear claw caramels biscuit caramels macaroon tart. Pudding powder chupa chups oat cake fruitcake candy canes lemon drops gummies tart. Cake cotton candy chocolate cake macaroon chupa chups pie pastry muffin. Bear claw caramels cake jelly-o cheesecake danish sweet roll. Soufflé caramels muffin cookie chocolate bar.",
        //     'location_detail' => "Jl. Tebet Barat I No.2, RT.1/RW.2, Tebet Bar., Kec. Tebet, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12810",
        //     'return_terms' => "Cupcake ipsum dolor sit amet. Powder dragée caramels jelly shortbread brownie pudding. Chocolate bar shortbread sesame snaps lemon drops tootsie roll. Bear claw caramels biscuit caramels macaroon tart. Pudding powder chupa chups oat cake fruitcake candy canes lemon drops gummies tart. Cake cotton candy chocolate cake macaroon chupa chups pie pastry muffin. Bear claw caramels cake jelly-o cheesecake danish sweet roll. Soufflé caramels muffin cookie chocolate bar.",
        //     'term_and_condition' => "Cupcake ipsum dolor sit amet. Powder dragée caramels jelly shortbread brownie pudding. Chocolate bar shortbread sesame snaps lemon drops tootsie roll. Bear claw caramels biscuit caramels macaroon tart. Pudding powder chupa chups oat cake fruitcake candy canes lemon drops gummies tart. Cake cotton candy chocolate cake macaroon chupa chups pie pastry muffin. Bear claw caramels cake jelly-o cheesecake danish sweet roll. Soufflé caramels muffin cookie chocolate bar.",
        // ]);
        for ($i = 0; $i < 2; $i++) {
            $banner = new Banner();
            $banner->file = "banner-" . $i + 1 . ".png";
            $banner->order = $i + 1;
            $banner->link_detail = "https://google.com";
            $banner->created_by = 1;
            $banner->updated_by = 1;
            $banner->save();
        }

        // for ($i = 0; $i < 2; $i++) {
        //     $appsMenu = new AppsMenu();
        //     $appsMenu->file = "menu-" . $i + 1 . ".svg";
        //     $appsMenu->order = $i + 1;
        //     $appsMenu->code = "menu-" . $i + 1 . ".svg";
        //     $appsMenu->created_by = 1;
        //     $appsMenu->updated_by = 1;
        //     $appsMenu->save();
        // }

        $user = new User();
        $user->name = "Sudoer";
        $user->email = "sudoer@gmail.com";
        $user->password = bcrypt("sudoer");
        $user->username = "sudoer";
        $user->created_by = 1;
        $user->updated_by = 1;
        $user->fullname = "Sudoer";
        $user->role_code = "SUPERUSER";
        $user->save();

        $user = new User();
        $user->name = "Admin";
        $user->email = "admin@gmail.com";
        $user->password = bcrypt("admin");
        $user->username = "admin";
        $user->created_by = 1;
        $user->updated_by = 1;
        $user->fullname = "Admin";
        $user->role_code = "ADMIN";
        $user->save();

        $navs = [
            [
                'name' => "MAIN NAVIGATION",
                'code' => "main-navi",
                'order' => 1
            ],
            [
                'name' => "ACL NAVIGATION",
                'code' => "acl-navi",
                'order' => 99
            ],
        ];
        foreach ($navs as $nav) {
            $navigation = new Navigation();
            $navigation->name = $nav["name"];
            $navigation->code = $nav["code"];
            $navigation->order = $nav["order"];
            $navigation->save();
        }

        $userRoles = [
            [
                "code" => "VIEWER",
                "view" => true,
                "create" => false,
                "update" => false,
                "delete" => false,
                "super" => false,
            ],
            [
                "code" => "ADMIN",
                "view" => true,
                "create" => true,
                "update" => true,
                "delete" => true,
                "super" => false,
            ],
            [
                "code" => "SUPERUSER",
                "view" => true,
                "create" => true,
                "update" => true,
                "delete" => true,
                "super" => true,
            ],
        ];
        foreach ($userRoles as $userRole) {
            $role = new UserRole();
            $role->code = $userRole["code"];
            $role->view = $userRole["view"];
            $role->create = $userRole["create"];
            $role->update = $userRole["update"];
            $role->delete = $userRole["delete"];
            $role->save();
        }

        $menus = [
            [
                'name' => "User Access Management",
                'code' => "acl-user",
                'status' => true,
                'nav_id' => 2,
                'icon' => "fas fa-blind",
                'order' => 99
            ],
            [
                'name' => "Menu Management",
                'code' => "acl-menu",
                'status' => true,
                'nav_id' => 2,
                'icon' => "fas fa-archway",
                'order' => 99
            ],
            [
                'name' => "Apps Management",
                'code' => "apps-management",
                'status' => true,
                'nav_id' => 1,
                'icon' => "fas fa-tachometer-alt",
                'order' => 1
            ],
            // [
            //     'name' => "Eats",
            //     'code' => "main",
            //     'status' => true,
            //     'nav_id' => 1,
            //     'icon' => "fas fa-tachometer-alt",
            //     'order' => 1
            // ],
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
                'name' => "User Access",
                'code' => "access-user",
                'status' => true,
                'menu_id' => 1,
                'path' => "access-users.index",
            ],
            [
                'name' => "Menu",
                'code' => "menu",
                'status' => true,
                'menu_id' => 2,
                'path' => "menu.index",
            ],
            [
                'name' => "Sub Menu",
                'code' => "sub-menu",
                'status' => true,
                'menu_id' => 2,
                'path' => "sub-menu.index",
            ],
            [
                'name' => "Navigation",
                'code' => "navi",
                'status' => true,
                'menu_id' => 2,
                'path' => "navi.index",
            ],
            [
                'name' => "Dashboard",
                'code' => "dashboard",
                'status' => true,
                'menu_id' => 3,
                'path' => "home",
            ],
            [
                'name' => "Role",
                'code' => "role",
                'status' => true,
                'menu_id' => 1,
                'path' => "role.index",
            ],
            [
                'name' => "Banner",
                'code' => "banner",
                'status' => true,
                'menu_id' => 3,
                'path' => "banner.index",
            ],
            [
                'name' => "Apps Menu",
                'code' => "apps-menu",
                'status' => true,
                'menu_id' => 3,
                'path' => "apps-menu.index",
            ],
            [
                'name' => "Event",
                'code' => "event",
                'status' => true,
                'menu_id' => 3,
                'path' => "event.index",
            ],
            [
                'name' => "Category Article",
                'code' => "category-article",
                'status' => true,
                'menu_id' => 3,
                'path' => "category-article.index",
            ],
            [
                'name' => "Promo",
                'code' => "promo",
                'status' => true,
                'menu_id' => 3,
                'path' => "promo.index",
            ],
            [
                'name' => "Promo Code",
                'code' => "promo-code",
                'status' => false,
                'menu_id' => 3,
                'path' => "promo-code.index",
            ],
            [
                'name' => "Bloc Location",
                'code' => "bloc-location",
                'status' => true,
                'menu_id' => 3,
                'path' => "bloc.index",
            ],
            [
                'name' => "Category Location",
                'code' => "category-lct",
                'status' => true,
                'menu_id' => 3,
                'path' => "category-lct.index",
            ],
            [
                'name' => "Location",
                'code' => "location",
                'status' => true,
                'menu_id' => 3,
                'path' => "location.index",
            ],
            [
                'name' => "Product Category",
                'code' => "product-category",
                'status' => true,
                'menu_id' => 3,
                'path' => "product-category.index",
            ],
            [
                'name' => "Product",
                'code' => "product",
                'status' => true,
                'menu_id' => 3,
                'path' => "product.index",
            ],
            [
                'name' => "User Activity",
                'code' => "user-activity",
                'status' => true,
                'menu_id' => 3,
                'path' => "user-activity.index",
            ],
            [
                'name' => "Beacon List",
                'code' => "beacon-list",
                'status' => true,
                'menu_id' => 3,
                'path' => "beacon.index",
            ],
            [
                'name' => "Beacon Art",
                'code' => "beacon-art",
                'status' => true,
                'menu_id' => 3,
                'path' => "beacon-art.index",
            ],
            [
                'name' => "Beacon Activity",
                'code' => "beacon-activity",
                'status' => true,
                'menu_id' => 3,
                'path' => "beacon-activity.index",
            ],
            [
                'name' => "User Apps",
                'code' => "user-apps",
                'status' => true,
                'menu_id' => 3,
                'path' => "user-apps.index",
            ],
            [
                'name' => "Visitor",
                'code' => "visitor",
                'status' => true,
                'menu_id' => 3,
                'path' => "visitor.index",
            ],
            [
                'name' => "Ticket Order",
                'code' => "ticket-order",
                'status' => true,
                'menu_id' => 3,
                'path' => "ticket-order.index",
            ],
            [
                'name' => "Otp",
                'code' => "otp",
                'status' => true,
                'menu_id' => 3,
                'path' => "otp.index",
            ],
            [
                'name' => "Article",
                'code' => "article",
                'status' => true,
                'menu_id' => 3,
                'path' => "article.index",
            ],
            [
                'name' => "Category Article",
                'code' => "category-article",
                'status' => true,
                'menu_id' => 3,
                'path' => "category-article.index",
            ],
            [
                'name' => "Channel Article",
                'code' => "channel-article",
                'status' => true,
                'menu_id' => 3,
                'path' => "channel-article.index",
            ],
            [
                'name' => "Tag Article",
                'code' => "tag-article",
                'status' => true,
                'menu_id' => 3,
                'path' => "tag-article.index",
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
                "menu_id" => 1,
                "sub_menu_id" => 1,
            ],
            [
                "user_id" => 1,
                "menu_id" => 2,
                "sub_menu_id" => 2,
            ],
            [
                "user_id" => 1,
                "menu_id" => 2,
                "sub_menu_id" => 3,
            ],
            [
                "user_id" => 1,
                "menu_id" => 2,
                "sub_menu_id" => 4,
            ],
            [
                "user_id" => 1,
                "menu_id" => 3,
                "sub_menu_id" => 5,
            ],
            [
                "user_id" => 1,
                "menu_id" => 1,
                "sub_menu_id" => 6,
            ],
            [
                "user_id" => 1,
                "menu_id" => 3,
                "sub_menu_id" => 7, //banner
            ],
            [
                "user_id" => 1,
                "menu_id" => 3,
                "sub_menu_id" => 8, // apps menu
            ],
            [
                "user_id" => 1,
                "menu_id" => 3,
                "sub_menu_id" => 14, //product category
            ],
            [
                "user_id" => 1,
                "menu_id" => 3,
                "sub_menu_id" => 15, //product
            ],
            [
                "user_id" => 1,
                "menu_id" => 3,
                "sub_menu_id" => 16, //user-activity
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
