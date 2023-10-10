<?php

namespace App\Http\Controllers\UserBundle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Navigation;
use App\DataTables\MenuDataTable;

class MenuController extends Controller
{
  public function index(MenuDataTable $dataTable)
  {
    return $dataTable->render('menu.index');
  }
  public function create()
  {
    $nav = Navigation::all();
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
    return view('menu.create', compact('nav', 'status'));
  }

  public function store(Request $request)
  {
    $menu = Menu::where('code', $request->code)->count();
    if ($menu > 0) return redirect()->back()->with('status', "Code is has ben used!")->with('success', false)->withInput($request->input());
    try {
      $status = 'Menu stored!';
      $success = true;
      Menu::create($request->all());
    } catch (\Throwable $e) {
      $status = $e->getMessage();
      $success = false;
    }

    return redirect()->route('menu.index')->with('status', $status)->with('success', $success);
  }

  public function edit($id)
  {
    $menu = Menu::find($id);
    $nav = Navigation::all();
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
    return view('menu.edit', compact('menu', 'nav', 'status'));
  }

  public function update(Request $request, $id)
  {
    $menu = Menu::find($id);
    $menus = Menu::where('code', $request->code)->where('id', '!=', $menu->id)->count();
    if ($menus > 0) return redirect()->back()->with('status', "Code is has ben used!")->with('success', false)->withInput($request->input());
    try {
      $status = 'Menu updated!';
      $success = true;
      $menu->update($request->all());
    } catch (\Throwable $e) {
      $status = $e->getMessage();
      $success = false;
    }
    return redirect()->route('menu.index')->with('status', $status)->with('success', $success);
  }

  public function destroy($id)
  {
    $menu = Menu::find($id);
    try {
      $status = "menu can't be deleted because it has a relationships!";
      $success = false;
      if ($menu->delete()) {
        $status = "menu deleted!";
        $success = true;
      };
    } catch (\Throwable $e) {
      $status = $e->getMessage();
      $success = false;
    }
    return redirect()->route('menu.index')->with('status', $status)->with('success', $success);
  }
}
