<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class QuizCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('quiz.category.index', compact('categories'));
    }

    public function create()
    {
        return view('quiz.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        Category::create([
            'title' => $request->title
        ]);

        return redirect()->route('category.index')->with('success', 'Category created');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('quiz.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title' => 'required']);

        $category = Category::findOrFail($id);
        $category->update(['title' => $request->title]);

        return redirect()->route('category.index')->with('success', 'Updated successfully');
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->route('category.index')->with('success', 'Deleted successfully');
    }
}

