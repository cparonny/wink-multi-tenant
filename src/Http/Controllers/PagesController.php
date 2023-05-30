<?php

namespace Wink\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Wink\Http\Resources\PagesResource;
use Wink\WinkPage;

class PagesController
{
    /**
     * Return pages.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $entries = WinkPage::when(request()->has('search'), function ($q) {
            $q->where('title', 'LIKE', '%' . request('search') . '%');
        })->with('website')
            ->orderBy('created_at', 'DESC')
            ->paginate(config('wink.pagination.pages', 30));

        return PagesResource::collection($entries);
    }

    /**
     * Return a single page.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id = null)
    {
        if ($id === 'new') {
            return response()->json([
                'entry' => WinkPage::make(['id' => Str::uuid()]),
            ]);
        }

        $entry = WinkPage::findOrFail($id);

        return response()->json([
            'entry' => $entry,
        ]);
    }

    /**
     * Store a single page.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id)
    {
        $data = [
            'title' => request('title'),
            'slug' => request('slug'),
            'website_id' => request('website_id'),
            'body' => request('body', ''),
            'meta' => request('meta', (object) []),
        ];

        validator($data, [
            'title' => 'required',
            'slug' => 'required|' . Rule::unique(config('wink.database_connection') . '.wink_pages')->where(function ($query) {
                $query->where('slug', request()->input('slug'))
                    ->where('website_id', request()->input('website_id'));
            })->ignore(request('id')),
        ])->validate();

        $entry = $id !== 'new' ? WinkPage::findOrFail($id) : new WinkPage(['id' => request('id')]);

        $entry->fill($data);

        $entry->save();

        return response()->json([
            'entry' => $entry,
        ]);
    }

    /**
     * Delete a single page.
     *
     * @param  string  $id
     * @return void
     */
    public function delete($id)
    {
        $entry = WinkPage::findOrFail($id);

        $entry->delete();
    }
}
