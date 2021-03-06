<?php

namespace App\Http\Controllers;

use App\Events\UpdateStock;
use App\Product;
use App\Providers\RestServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    const DEFAULT_PAGINATION_SIZE = 30;

    /**
     * Return products list paginated
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getProductsList()
    {
        return DB::table(Product::TABLE_NAME)->paginate(self::DEFAULT_PAGINATION_SIZE);
    }

    /**
     * Get the product detail
     *
     * @param string $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getProductDetail($id)
    {
        // Get the product matching the provided id
        $mixResult = Product::find($id);
        // Initialise the response code
        $intReturnCode = Response::HTTP_OK;
        // If the product is not found return the correct code
        if (is_null($mixResult)) {
            $intReturnCode = Response::HTTP_NOT_FOUND;
        }

        return response($mixResult, $intReturnCode);
    }

    /**
     * Update the chosen product with provided data
     *
     * @param Request $objRequest
     * @param string $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function updateProduct(Request $objRequest, $id)
    {
        // List the required parameters
        $arrHandledParameters = [
            Product::FIELD_NAME,
            Product::FIELD_DESC,
            Product::FIELD_QUANTITY,
        ];
        // Initialise the response (code and content) in case of error missing parameter
        $intReturnCode = Response::HTTP_BAD_REQUEST;
        $mixResult = RestServiceProvider::generateAtLeastOneParametersRequiredError($arrHandledParameters);
        // If the request is valid (contains at least one required parameters)
        if ($objRequest->anyFilled($arrHandledParameters)) {
            // Initialise the response (code and content) in case of success
            $intReturnCode = Response::HTTP_NO_CONTENT;
            $mixResult = null;
            /** @var Product|null $mixProduct */
            $mixProduct = Product::find($id);
            if (is_null($mixProduct)) {
                $intReturnCode = Response::HTTP_NOT_FOUND;
                $mixResult = RestServiceProvider::generateEntityNotFound('Product');
            } else {
                // Retrieve the provided parameters
                $arrProvidedParameters = $objRequest->only($arrHandledParameters);
                // Compute the new quantity
                $intNewQuantity = $arrProvidedParameters[Product::FIELD_QUANTITY] ?? $mixProduct->quantity;
                // Compute the stock diff
                $arrProvidedParameters['quantity_diff'] = $intNewQuantity - $mixProduct->quantity;
                // Update the products
                $mixProduct->update($arrProvidedParameters);
                // Update the total price
                $mixProduct->updateTotalPrice();
                // Trigger UpdateStock event
                event(new UpdateStock($mixProduct, $arrProvidedParameters));
            }
        }

        return response($mixResult, $intReturnCode);
    }

    /**
     * Create a new product with the provided data
     *
     * @param Request $objRequest
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function createProduct(Request $objRequest)
    {
        // List the required parameters
        $arrParametersValidation = [
            Product::FIELD_NAME => 'required|string|max:255',
            Product::FIELD_DESC => 'required|string|max:255',
            Product::FIELD_QUANTITY => 'required|integer',
        ];
        $arrParametersName = array_keys($arrParametersValidation);
        $objValidator = Validator::make($objRequest->all(), $arrParametersValidation);
        // If the request is valid (contains at least one required parameters)
        if (!$objValidator->fails()) {
            $intReturnCode = Response::HTTP_CREATED;
            /** @var Product $objProduct */
            $objProduct = Product::create($objRequest->only($arrParametersName));
            $mixResult = $objProduct->id;
        } else {
            // Initialise the response (code and content) in case of error missing parameter
            $intReturnCode = Response::HTTP_BAD_REQUEST;
            $mixResult = RestServiceProvider::generateCustomError(
                $objValidator->errors(),
                RestServiceProvider::EC_PARAMETERS_REQUIRED
            );
        }

        return response($mixResult, $intReturnCode);
    }


    /**
     * Get the product detail
     *
     * @param string $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteProduct($id)
    {
        // Get the product matching the provided id
        /** @var Product $mixResult */
        $mixResult = Product::find($id);
        // Initialise the response code
        $intReturnCode = Response::HTTP_NO_CONTENT;
        // If the product is not found return the correct code
        if (is_null($mixResult)) {
            $intReturnCode = Response::HTTP_NOT_FOUND;
        } else {
            // Delete the product
            try {
                $mixResult->delete();
            } catch (\Exception $objException) {
                // Return a proper error code
                $intReturnCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                $mixResult = RestServiceProvider::generateCustomError($objException->getMessage());
            }
        }

        return response($mixResult, $intReturnCode);
    }
}
