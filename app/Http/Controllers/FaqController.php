<?php

namespace App\Http\Controllers;

use App\Interfaces\FAQRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FAQController extends Controller
{
    public function __construct(private readonly FAQRepositoryInterface $faqEepositoryInterface)
    {
    }

    /**
     * Show public FAQs page, optionally filtered by category.
     */
    public function index(Request $request): View
    {
        $selectedCategory = $request->query('category');
        $faqs = $this->faqEepositoryInterface->getActive($selectedCategory);
        $categories = $this->faqEepositoryInterface->getCategories();

        return view('faqs.index', [
            'faqs' => $faqs,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }
}
