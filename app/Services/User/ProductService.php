<?php

namespace App\Services\User;

use App\Http\Resources\User\ProductResource;
use App\Repositories\User\ProductRepository;
use App\Services\BaseService;

class ProductService extends BaseService
{
    public function __construct(protected ProductRepository $productRepository)
    {
    }

    public function getAllProducts()
    {
        $products = $this->productRepository->getAll();
        return $this->returnData(ProductResource::collection($products),__('All Products'));
    }

    public function getProductById($id)
    {
        $product = $this->productRepository->findById($id);
        if (!$product){
            return $this->errorMessage('Product is not exists',404);
        }
        return $this->returnData(new ProductResource($product),__('Show Product'));
    }
}
