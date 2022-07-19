<?php

namespace App\Http\Controllers\Views;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Product;
use \stdClass;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    public function index($searchWord)
    {
        $product = Product::where('productName', 'like', '%' . $searchWord . '%')->get();

        $service = Service::where('serviceName', 'like', '%' . $searchWord . '%')->get();

        $searchResultCount = count($service) + count($product);

        $searchArray = array();

        for ($i = 0; $i < count($product); $i++) {
            $searchObj = new \stdClass();
            $searchObj->title = $product[$i]->productName;
            $searchObj->desc = $product[$i]->productShortDesc;
            $searchObj->type = 'product';
            $searchObj->id = $product[$i]->id;

            array_push($searchArray, $searchObj);
        }

        for ($i = 0; $i < count($service); $i++) {
            $searchObj = new \stdClass();
            $searchObj->title = $service[$i]->serviceName;
            $searchObj->desc = $service[$i]->serviceFirstContentTitle;
            $searchObj->type = 'service';
            $searchObj->id = $service[$i]->id;

            array_push($searchArray, $searchObj);
        }

        $data = $this->paginate($searchArray, $searchWord);

        return view('searchResult', [
            'title' => "Search results for '" . $searchWord . "' - IDO'S Clinic",
            "description" => "Welcome to IDO's Clinic, a place where science, medicine and arts meet. State of the art lasers and aesthetics innovations to restore and enhance your skin, face and body.",
            "searchWord" => $searchWord,
            'searchResultCount' => $searchResultCount,
            'searchResult' =>  $data
        ]);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function paginate($items, $searchWord, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            ['path' => url('searchResult/' . $searchWord)]
        );
    }
}
