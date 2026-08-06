<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLandingContentRequest;
use App\Models\LandingContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingContentController extends Controller
{
    public function index(Request $request, string $type): View
    {
        $label = LandingContent::label($type);
        $items = LandingContent::where('type', $type)->orderBy('display_order')->paginate(15);

        return view('admin.landing-content.index', compact('items', 'type', 'label'));
    }

    public function create(string $type): View
    {
        return $this->form($type, new LandingContent(['type' => $type, 'status' => true]));
    }

    public function store(StoreLandingContentRequest $request, string $type): RedirectResponse
    {
        LandingContent::label($type);
        $data = $this->payload($request, new LandingContent);
        $data['type'] = $type;
        LandingContent::create($data);

        return to_route('admin.landing-content.index', $type)->with('success', 'Landing content created.');
    }

    public function edit(string $type, LandingContent $landingContent): View
    {
        $this->guardType($type, $landingContent);

        return $this->form($type, $landingContent);
    }

    public function update(StoreLandingContentRequest $request, string $type, LandingContent $landingContent): RedirectResponse
    {
        $this->guardType($type, $landingContent);
        $landingContent->update($this->payload($request, $landingContent));

        return to_route('admin.landing-content.index', $type)->with('success', 'Landing content updated.');
    }

    public function destroy(string $type, LandingContent $landingContent): RedirectResponse
    {
        $this->guardType($type, $landingContent);
        $landingContent->delete();

        return to_route('admin.landing-content.index', $type)->with('success', 'Landing content deleted.');
    }

    private function form(string $type, LandingContent $item): View
    {
        $label = LandingContent::label($type);

        return view('admin.landing-content.form', compact('item', 'type', 'label'));
    }

    private function payload(StoreLandingContentRequest $request, LandingContent $item): array
    {
        $data = $request->validated();
        $data['image'] = $item->image;
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('landing-content', 'public');
        } elseif (! $request->filled('existing_image')) {
            $data['image'] = null;
        }
        unset($data['existing_image']);

        return $data;
    }

    private function guardType(string $type, LandingContent $item): void
    {
        LandingContent::label($type);
        abort_unless($item->type === $type, 404);
    }
}
