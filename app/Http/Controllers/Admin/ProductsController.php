<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Utils\Admin\UploadFile;
use Illuminate\Http\Request;
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
            $products = $this->product->where('status', 1)->paginate(2);
            $data = $products->values()->toArray();
            return response()->json($data);
        }
        $products = $this->product->where('status', 1)->paginate(2);
        $fields = $this->product->getTableColumns();
//        array_push($fields, '操作');
        $data = $products->values()->toArray();
        $data = array_map(function ($val) {
            return array_values($val);
        }, $data);

        return view('admin.products.index', compact('products', 'fields', 'data'));
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
        $coverImg = $request->file('cover_img')->storeAs('images', $request->file('cover_img')->getClientOriginalName());
        $data['cover_img'] = $coverImg;
        Product::query()->create($data);

        return redirect()->route('products.index')->with('toast_success', 'Task Created Successfully!');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
