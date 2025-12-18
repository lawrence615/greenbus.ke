<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FAQ\StoreRequest;
use App\Http\Requests\FAQ\UpdateRequest;
use App\Interfaces\FaqRepositoryInterface;
use App\Interfaces\TourCategoryRepositoryInterface;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function __construct(
        protected FaqRepositoryInterface $faqRepository,
        protected TourCategoryRepositoryInterface $tourCategoryRepository
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category', 'is_active']);
        $faqs = $this->faqRepository->index($filters);
        $categories = $this->faqRepository->getCategories();

        return view('admin.faqs.index', compact('faqs', 'categories'));
    }

    public function create()
    {
        $categories = $this->faqRepository->getCategories();
        $tourCategories = $this->tourCategoryRepository->getAll();
        return view('admin.faqs.create', compact('categories', 'tourCategories'));
    }

    public function store(StoreRequest $request)
    {
        $this->faqRepository->store($request->validated());

        return redirect()
            ->route('console.faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit(FAQ $faq)
    {
        $categories = $this->faqRepository->getCategories();
        $tourCategories = $this->tourCategoryRepository->getAll();
        return view('admin.faqs.edit', compact('faq', 'categories', 'tourCategories'));
    }

    public function update(UpdateRequest $request, FAQ $faq)
    {
        $this->faqRepository->update($faq, $request->validated());

        return redirect()
            ->route('console.faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(FAQ $faq)
    {
        $this->faqRepository->delete($faq);

        return redirect()
            ->route('console.faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }

    public function toggleStatus(FAQ $faq)
    {
        $this->faqRepository->toggleStatus($faq);
        $status = $faq->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', "FAQ has been {$status}.");
    }
}
