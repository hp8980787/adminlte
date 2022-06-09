<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use App\Utils\Admin\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class ProductsController extends Controller
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View|array
     */
    public function index(ProductRequest $request)
    {

        if ($request->ajax()) {
            $perPage = $request->perPage ?: 20;
            $query = $this->product->where('status', 1);
            if ($request->search) {
                $search = $request->search;
                $query->where('name', 'like', "%$search%")
                    ->orWhere('category', 'like', "%$search%")
                    ->orWhere('replace', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('sku', 'like', "%$search%");
            }
            $products = $query->paginate($perPage);

            return response()->json(new ProductCollection($products));
        }
        return view('admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $fillable = $this->product->getFillable();
        $data = $request->only($fillable);
        $coverImg = Storage::disk('images')->put('product', $request->file('cover_img'));
        $data['cover_img'] = $coverImg;
        if ($request->file('ifMultiple')) {
            $imgs = [];
            foreach ($request->ifMultiple as $file) {

                $file= Storage::disk('images')->put('product', $file);

                $imgs[] = Storage::disk('images')->url($file);
            }
            $data['imgs'] = join('|', $imgs);
        }

        Product::query()->create($data);

        return redirect(adminRoute('products.index'))->with('toast_success', 'Task Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::query()->findOrFail($id);

        return \view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only($this->product->getFillable());
        $product = $this->product->findOrFail($id);
        if ($request->cover_img) {
            Storage::disk('images')->delete($product->cover_img);
            $file = Storage::disk('images')->put('product', $request->file('cover_img'));
            $url = Storage::disk('images')->url($file);
            $data['cover_img'] = $url;

        }
        $this->product->where('id', $id)->update($data);
        return redirect(adminRoute('products.index'))->with('toast_success', '修改成功!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::query()->where('id', $id)->delete();

        return response('删除成功！', 200);
    }
}
