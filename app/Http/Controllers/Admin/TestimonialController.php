<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Testimonial\StoreRequest;
use App\Http\Requests\Testimonial\UpdateRequest;
use App\Interfaces\TestimonialRepositoryInterface;
use App\Models\Testimonial;
use App\Models\Tour;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function __construct(
        protected TestimonialRepositoryInterface $testimonialRepository
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'is_active']);
        $testimonials = $this->testimonialRepository->index($filters);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        $tours = Tour::orderBy('title')->get(['id', 'title']);

        return view('admin.testimonials.create', compact('tours'));
    }

    public function store(StoreRequest $request)
    {
        $this->testimonialRepository->store($request->validated());

        return redirect()
            ->route('console.testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial)
    {
        $tours = Tour::orderBy('title')->get(['id', 'title']);

        return view('admin.testimonials.edit', compact('testimonial', 'tours'));
    }

    public function update(UpdateRequest $request, Testimonial $testimonial)
    {
        $this->testimonialRepository->update($testimonial, $request->validated());

        return redirect()
            ->route('console.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $this->testimonialRepository->delete($testimonial);

        return redirect()
            ->route('console.testimonials.index')
            ->with('success', 'Testimonial deleted successfully.');
    }

    public function toggleStatus(Testimonial $testimonial)
    {
        $this->testimonialRepository->toggleStatus($testimonial);
        $status = $testimonial->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', "Testimonial has been {$status}.");
    }
}
