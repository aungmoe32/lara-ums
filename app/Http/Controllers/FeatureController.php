<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeatureStoreRequest;
use App\Http\Requests\FeatureUpdateRequest;
use App\Models\Feature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeatureController extends Controller
{
    public function index(Request $request): View
    {
        $features = Feature::all();

        return view('feature.index', [
            'features' => $features,
        ]);
    }

    public function create(Request $request): View
    {
        return view('feature.create');
    }

    public function store(FeatureStoreRequest $request): RedirectResponse
    {
        $feature = Feature::create($request->validated());

        $request->session()->flash('feature.id', $feature->id);

        return redirect()->route('features.index');
    }

    public function show(Request $request, Feature $feature): View
    {
        return view('feature.show', [
            'feature' => $feature,
        ]);
    }

    public function edit(Request $request, Feature $feature): View
    {
        return view('feature.edit', [
            'feature' => $feature,
        ]);
    }

    public function update(FeatureUpdateRequest $request, Feature $feature): RedirectResponse
    {
        $feature->update($request->validated());

        $request->session()->flash('feature.id', $feature->id);

        return redirect()->route('features.index');
    }

    public function destroy(Request $request, Feature $feature): RedirectResponse
    {
        $feature->delete();

        return redirect()->route('features.index');
    }
}
