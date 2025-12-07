<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Faq\StoreRequest;
use App\Http\Requests\Faq\UpdateRequest;
use App\Interfaces\FaqRepositoryInterface;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function __construct(
        protected FaqRepositoryInterface $faqRepository
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
        return view('admin.faqs.create', compact('categories'));
    }

    public function store(StoreRequest $request)
    {
        $this->faqRepository->store($request->validated());

        return redirect()
            ->route('console.faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        $categories = $this->faqRepository->getCategories();
        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    public function update(UpdateRequest $request, Faq $faq)
    {
        $this->faqRepository->update($faq, $request->validated());

        return redirect()
            ->route('console.faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $this->faqRepository->delete($faq);

        return redirect()
            ->route('console.faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }

    public function toggleStatus(Faq $faq)
    {
        $this->faqRepository->toggleStatus($faq);
        $status = $faq->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', "FAQ has been {$status}.");
    }
}
