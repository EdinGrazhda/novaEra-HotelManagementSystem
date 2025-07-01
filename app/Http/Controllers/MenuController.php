<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Constructor to apply authorization middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Add role-based middleware for specific actions
        $this->authorizeResource(Menu::class, 'menu');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('menu.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('menu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:menu,name',
                'description' => 'required|string',
            ]);

            Menu::create($request->all());

            return redirect()->route('menu.index')
                            ->with('success', 'Menu item created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                            ->withErrors($e->validator)
                            ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                            ->withErrors(['name' => 'A menu item with this name already exists.'])
                            ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return view('menu.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        return view('menu.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:menu,name,' . $menu->id,
                'description' => 'required|string',
            ]);

            $menu->update($request->all());

            return redirect()->route('menu.index')
                            ->with('success', 'Menu item updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                            ->withErrors($e->validator)
                            ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                            ->withErrors(['name' => 'A menu item with this name already exists.'])
                            ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('menu.index')
                        ->with('success', 'Menu item deleted successfully.');
    }
}
