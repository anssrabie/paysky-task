<?php

namespace App\Http\Controllers\Ai\V1\User;

use App\Http\Controllers\Controller;
use App\Services\User\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->productService->getAllProducts();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->productService->getProductById($id);
    }

}
