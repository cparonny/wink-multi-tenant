<?php

namespace Wink\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Wink\Http\Resources\WebsitesResource;
use Wink\WinkWebsite;

class WebsitesController
{
    /**
     * Return posts.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $entries = WinkWebsite::when(request()->has('search'), function ($q) {
            $q->where('name', 'LIKE', '%' . request('search') . '%');
        })
            ->orderBy('created_at', 'DESC')
            ->withCount('posts')
            ->paginate(config('wink.pagination.tags', 30));

        return WebsitesResource::collection($entries);
    }

    /**
     * Return a single post.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id = null)
    {
        if ($id === 'new') {
            return response()->json([
                'entry' => WinkWebsite::make([
                    'id' => Str::uuid(),
                ]),
            ]);
        }

        $entry = WinkWebsite::findOrFail($id);

        return response()->json([
            'entry' => $entry,
        ]);
    }

    /**
     * Store a single category.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id)
    {
        $data = [
            'name' => request('name'),
            'domain' => request('domain'),
            'meta' => request('meta', (object) []),
        ];

        validator($data, [
            'name' => 'required',
            'domain' => 'required|' . Rule::unique(config('wink.database_connection') . '.wink_websites', 'domain')->ignore(request('id')),
        ])->validate();

        $entry = $id !== 'new' ? WinkWebsite::findOrFail($id) : new WinkWebsite(['id' => request('id')]);

        $entry->fill($data);

        $entry->save();

        return response()->json([
            'entry' => $entry->fresh(),
        ]);
    }

    /**
     * Return a single tag.
     *
     * @param  string  $id
     * @return void
     */
    public function delete($id)
    {
        $entry = WinkWebsite::findOrFail($id);

        $entry->delete();
    }
}
