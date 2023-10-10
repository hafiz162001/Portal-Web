<?php

namespace App\Http\Controllers\UserBundle;

use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DataTables\SubMenuDataTable;
use App\Http\Controllers\Controller;

class SubMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SubMenuDataTable $dataTable)
    {
        return $dataTable->render('sub-menu.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menu = Menu::all();
        $status = [
            [
                "name" => "UNPUBLISHED",
                "val" => false,
            ],
            [
                "name" => "PUBLISHED",
                "val" => true,
            ],
        ];
        return view('sub-menu.create', compact('menu', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $menu = SubMenu::where('code', $request->code)->count();
        if ($menu > 0) return redirect()->back()->with('status', "Code is has ben used!")->with('success', false)->withInput($request->input());
        try {
            $status = 'SubMenu stored!';
            $success = true;
            // SubMenu::create($request->all());

            $id = DB::table('sub_menus')->max('id');

            SubMenu::create([
                'id' => $id + 1,
                'menu_id' => $request->menu_id,
                'name' => $request->name,
                'code' => $request->code,
                'status' => $request->status,
                'path' => $request->path,
                'order' => $request->order,
            ]);
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }

        return redirect()->route('sub-menu.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subMenu = SubMenu::find($id);
        $menu = Menu::all();
        $status = [
            [
                "name" => "UNPUBLISHED",
                "val" => false,
            ],
            [
                "name" => "PUBLISHED",
                "val" => true,
            ],
        ];
        return view('sub-menu.edit', compact('menu', 'subMenu', 'status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $subMenu = SubMenu::find($id);
        $subMenus = SubMenu::where('code', $request->code)->where('id', '!=', $subMenu->id)->count();
        if ($subMenus > 0) return redirect()->back()->with('status', "Code is has ben used!")->with('success', false)->withInput($request->input());
        try {
            $status = 'Menu updated!';
            $success = true;
            $subMenu->update($request->all());
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('sub-menu.index')->with('status', $status)->with('success', $success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subMenu = SubMenu::find($id);
        try {
            $status = "menu can't be deleted because it has a relationships!";
            $success = false;
            if ($subMenu->delete()) {
                $status = "menu deleted!";
                $success = true;
            };
        } catch (\Throwable $e) {
            $status = $e->getMessage();
            $success = false;
        }
        return redirect()->route('sub-menu.index')->with('status', $status)->with('success', $success);
    }
}
