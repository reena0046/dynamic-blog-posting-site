<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogStoreRequest;
use App\Http\Requests\Admin\BlogUpdateRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.blogs.index');
    }

    /**
     * Display a listing of the resource in datatable.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        $query = Blog::query();

        $sort = $request->input('sort', 'newest');

        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
        } elseif ($sort === 'az') {
            $query->orderBy('title', 'asc');
        } else {
            $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        }

        return DataTables::eloquent($query)
            ->editColumn('title', function ($blog) {
                return $blog->title;
            })
            ->editColumn('description', function ($blog) {
                return Str::limit(strip_tags($blog->description), 80);
            })
            ->addColumn('status', function ($blog) {
                $checked = $blog->status === 'ACTIVE' ? 'checked' : '';

                return '<div class="form-check form-switch d-flex justify-content-center">
                    <input class="form-check-input blog-status-switch" type="checkbox" data-id="' . $blog->id . '" ' . $checked . '>
                </div>';
            })
            ->addColumn('action', function ($blog) {

                $editUrl = route('admin.blogs.edit', $blog->id);
                $showUrl = route('admin.blogs.show', ['blog' => $blog->id]);

                $edit = '<a href="' . $editUrl . '" class="blog-action-btn blog-action-edit" title="Edit" aria-label="Edit">' .
                    '<i class="fa fa-edit"></i>' .
                    '</a>';

                $show = '<a href="' . $showUrl . '" class="blog-action-btn blog-action-view modal-one-btn" ' .
                    'data-entity="blogs" data-title="Blog Details" data-modal-size="lg" data-modal-scrollable="true" ' .
                    'data-route-key="' . $blog->id . '" title="View" aria-label="View">' .
                    '<i class="fa fa-eye"></i>' .
                    '</a>';

                return '<div class="d-flex align-items-center justify-content-center blog-action-cell">' .
                    $edit . $show .
                    '</div>';
            })
            ->addIndexColumn()
            ->rawColumns(['title', 'description', 'status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.blogs.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogStoreRequest $request)
    {
        $blog = new Blog();
        $blog->fill($request->safe()->except(['thumbnail_image', 'banner_image', 'schema_markup']));
        $blog->applySlug($request);
        $blog->applySchemaMarkup($request);
        $blog->storeMedia($request);
        $blog->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog Created Successfully',
            'blog' => $blog,
        ], 201);
    }

    public function show(Blog $blog)
    {
        return view('admin.blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        return view('admin.blogs.form', compact('blog'));
    }

    /**
     * Upload an image to the blog content.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'upload' => 'required|image',
        ]);

        $path = Storage::disk('public')->put('blog/content', $request->file('upload'));
        $url = asset(Storage::url($path));

        if ($request->has('CKEditorFuncNum')) {
            $funcNum = $request->input('CKEditorFuncNum');

            return response(
                '<script>window.parent.CKEDITOR.tools.callFunction(' . (int) $funcNum . ', ' . json_encode($url) . ', "");</script>'
            )->header('Content-Type', 'text/html; charset=utf-8');
        }

        return response()->json([
            'uploaded' => 1,
            'fileName' => $request->file('upload')->getClientOriginalName(),
            'url' => $url,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogUpdateRequest $request, Blog $blog)
    {
        $blog->fill($request->safe()->except(['thumbnail_image', 'banner_image', 'schema_markup']));
        $blog->applySlug($request);
        $blog->applySchemaMarkup($request);
        $blog->updateMedia($request);
        $blog->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog Updated Successfully',
            'blog' => $blog,
        ], 200);
    }

    /**
     * Toggle blog status between ACTIVE and INACTIVE.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:blogs,id',
            'status' => 'required|in:ACTIVE,INACTIVE',
        ]);

        $blog = Blog::findOrFail($request->id);
        $blog->updateStatus($request->status);

        return response()->json([
            'status' => 'success',
            'message' => 'Blog status updated successfully',
        ]);
    }
}
