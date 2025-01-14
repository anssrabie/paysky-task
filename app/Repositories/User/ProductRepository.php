<?php

namespace App\Repositories\User;

use App\Models\Product;

class ProductRepository
{
    public function __construct(protected Product $productModel)
    {
    }

    public function getAll()
    {
        return $this->productModel->all();
    }

    public function findById($id)
    {
        return $this->productModel->find($id);
    }


}
