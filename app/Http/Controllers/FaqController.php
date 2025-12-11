<?php

namespace App\Http\Controllers;

use App\Interfaces\FaqRepositoryInterface;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function __construct(private readonly FaqRepositoryInterface $faqs)
    {
    }

    /**
     * Show public FAQs page.
     */
    public function index(): View
    {
        $faqs = $this->faqs->getPublicFaqs();
        $categories = $this->faqs->getCategories();

        return view('faqs.index', [
            'faqs' => $faqs,
            'categories' => $categories,
        ]);
    }
}
