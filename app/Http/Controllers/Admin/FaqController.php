<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Faq\StoreRequest;
use App\Http\Requests\Faq\UpdateRequest;
use App\Interfaces\FaqRepositoryInterface;
use App\Interfaces\TourCategoryRepositoryInterface;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function __construct(
        protected FaqRepositoryInterface $faqRepositoryInterface,
        protected TourCategoryRepositoryInterface $tourCategoryRepository
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category', 'is_active']);
        $faqs = $this->faqRepositoryInterface->index($filters);
        $categories = $this->faqRepositoryInterface->getCategories();

        return view('admin.faqs.index', compact('faqs', 'categories'));
    }

    public function create()
    {
        $categories = $this->faqRepositoryInterface->getCategories();
        $tourCategories = $this->tourCategoryRepository->getAll();
        return view('admin.faqs.create', compact('categories', 'tourCategories'));
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->faqRepositoryInterface->store($validated);

            return redirect()->route('console.faqs.index')->with('success', 'FAQ created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create FAQ. Please try again.');
        }
    }

    public function edit(Faq $faq)
    {
        $categories = $this->faqRepositoryInterface->getCategories();
        $tourCategories = $this->tourCategoryRepository->getAll();
        return view('admin.faqs.edit', compact('faq', 'categories', 'tourCategories'));
    }

    public function update(UpdateRequest $request, Faq $faq)
    {
        $validated = $request->validated();

        try {
            $this->faqRepositoryInterface->update($faq, $validated);

            return redirect()->route('console.faqs.index')->with('success', 'FAQ updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update FAQ. Please try again.');
        }
    }

    public function destroy(Faq $faq)
    {
        $this->faqRepositoryInterface->delete($faq);

        return redirect()
            ->route('console.faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }

    public function toggleStatus(Faq $faq)
    {
        $this->faqRepositoryInterface->toggleStatus($faq);
        $status = $faq->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', "FAQ has been {$status}.");
    }
}
