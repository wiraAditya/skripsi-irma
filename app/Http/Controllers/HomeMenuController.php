<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Menu;
use App\Models\Meja;


class HomeMenuController extends HomeBaseController
{
    public function __construct()
    {
        parent::__construct(); // Add this line to call parent constructor
    }
    
    public function index(Request $request)
    {
        // Get the table if provided
        $tableName = $request->query('mejaId');
        $table = null;
        
        if ($tableName) {
            $table = Meja::where('id', $tableName)->first();
        }
        // Get all active menu categories
        $menuCategories = Kategori::where('is_active', true)->get();
        
        // Get category filter from query parameter if it exists
        $categoryId = $request->query('category', 0);
        
        // Get filtered menu items based on category
        $menus = $this->getFilteredMenus($categoryId);
        
        // Get the current category for highlighting in the UI
        $currentCategoryId = $categoryId;
        
        return view('home.index', compact('menus', 'menuCategories', 'table', 'currentCategoryId'));
    }

    private function getFilteredMenus($categoryId)
    {
        if ($categoryId == 0) {
            // Return all active menus if category is "All"
            return Menu::active()->get();
        } else {
            // Return menus filtered by category
            return Menu::active()
                ->where('menu_category_id', $categoryId)
                ->get();
        }
    }
    
}