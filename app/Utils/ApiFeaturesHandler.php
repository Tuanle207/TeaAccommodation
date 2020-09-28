<?php

namespace App\Utils;

use App\Apartment;
use App\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;


class ApiFeaturesHandler {

    public Builder $query;
    public $queryString;
    public $modelType;
    public $id;
    private $meta;

    /**
     * * Constructor for ApiFeaturesHanlder
     * @query: Database query
     * @queryString: query string from request
     * @modelType: apartment/comments
     * @id: related id object (is optional)
     */
    function __construct($query, $queryString, $modelType, $id = null) {
        $this->query = $query;
        $this->queryString = $queryString;
        $this->modelType = $modelType;
        $this->id = $id;
        $this->meta = null;
    }

    /**
     ** user Identider for building query
     */
    public function useIdentifier() {
        if ($this->modelType === 'apartment' && $this->id != null) {
            $this->query = $this->query->where('postedBy', $this->id)->where('visible', 1);
        } 
        else if ($this->modelType === 'comment' && $this->id != null) {
            $this->query = $this->query->where('idApartment', $this->id);
        }

        return $this;
    }

    /**
     ** Filter for query string from request
     */
    public function filter() {

        //* 1.) format logical operator
        $patterns = ['/gte/', '/gt/', '/lte/', '/lt/'];
        $replacements = ['>=', '>', '<=', '<'];
                
        $queryStr = json_encode($this->queryString);
        $queryStr = preg_replace($patterns, $replacements, $queryStr);
        $queryStr = json_decode($queryStr);

        //* 2.) filtering based on condition

            // ! For apartment
        if ($this->modelType === 'apartment') {

            // * firstly we just get visible and available apartment (active, available)
            $this->query = $this->query->where('visible', 1)->where('status', 'còn phòng');

            // * only accept one in district / geo point
            if (property_exists($queryStr, 'district') 
            && property_exists($queryStr, 'latitude')
            && property_exists($queryStr, 'longitude')) {
                throw new HttpResponseException(response()->json([
                    'status' => 'fail',
                    'message' => 'Thông tin về quận/huyện và tọa độ không thể xuất hiện cùng một lúc'
                ], 400));
            }

                // * filters for many cases 
            $basicFields = ['rent', 'area', 'rating'];


                // * case 1: is there districts list
            if (property_exists($queryStr, 'district')) {
                $districts = explode(',', $queryStr->district);
                // join with address table
                $this->query->join('addresses', 'apartments.address', '=', 'addresses.id');
                $this->query = $this->query->whereIn('district', $districts);
                $this->query = $this->query->select('apartments.*'); 
            }  

                // * case 2: is there a geo points
            else if (property_exists($queryStr, 'latitude') && property_exists($queryStr, 'longitude')) {
                $latitude = (double) $queryStr->latitude;
                $longitude = (double) $queryStr->longitude;
                $radius = (int) property_exists($queryStr, 'radius') ? $queryStr->radius : 15;
                
                $this->query = $this->query->whereRaw("
                    ST_Distance_Sphere (
                        point(longitude, latitude),
                        point(?,?)
                    ) * 0.001 < ?
                ", [$longitude, $latitude, $radius]);
            }

            // * handle apartment facilities query
            if (property_exists($queryStr, 'facilities')) {
                $facilities = explode(',', $queryStr->facilities);
                foreach ($facilities as $k => $fac)
                    $this->query = $this->query
                        ->where('facilities', 'like', '%'.json_encode($fac, JSON_UNESCAPED_UNICODE).'%');
            }


            
        }// ! For comments
        else if ($this->modelType === 'comment') {
            // *? not required now
        }


        // * remaining fields
        foreach ($queryStr as $field => $value)
        //* handle others
        if (in_array($field, $basicFields)) {
            if (gettype($value) === 'object') {
                $this->query = $this->query->where(function($query) use($field, $value) {
                    foreach ($value as $operator => $val)
                        $query = $query->where($field, $operator, $val);
                });
            }
            else {
                $this->query = $this->query->where($field, $value);
            }
        }
        
        return $this;
    }

    /**
     ** Sorting based on fields
     */
    public function sort() {

        // * filter for each particular model
        $sortFilter = [];

        //* is there user-defined fields? raw sort from request 
        $rawSorts = property_exists($this->queryString, 'sort')
        ? explode(',', $this->queryString->sort)
        : [];

        //! For apartment
        if ($this->modelType === 'apartment') {
            $sortFilter = ['rent', 'area', 'rating', 'postedAt', 'lastUpdatedAt'];
            $this->query = $this->query->orderBy('views', 'desc');
            if (count($rawSorts) === 0) {
                $rawSorts = ['rating', 'lastUpdatedAt:desc'];
            }
        }//! For comment
        else if ($this->modelType === 'comment') {
            //? do not need right now
            $sortFilter = ['commentedAt'];
            if (count($rawSorts) === 0) {
                $rawSorts = ['commentedAt:desc'];
            }
        }


        // * format sorts object
        $sorts = [];
        foreach ($rawSorts as $index => $value) {

            // * object has 2 property, 1 for field name, 1 for sorting order, then push to 'sorts' array
            $splittedValues = explode(':', $value);

            if (in_array($splittedValues[0], $sortFilter)) {

                if (count($splittedValues) ===  2  && in_array($splittedValues[1], ['asc', 'desc'])) {
                    $sort = (object)[
                        'field' => $splittedValues[0],
                        'order' => $splittedValues[1]
                    ];
                    array_push($sorts, $sort);
                }
                else if (count($splittedValues) === 1) {
                    $sort = (object)[
                        'field' => $splittedValues[0],
                        'order' =>  'asc'
                    ];
                    array_push($sorts, $sort);
                };
            }
        }
            

        foreach ($sorts as $index => $sort) {
            $this->query = $this->query->orderBy($sort->field, $sort->order);
        }
        
        return $this;
    }

    /**
     ** Limit necessary fields
     */
    public function limitFields() {
        
        //* is there user-defined fields?
        $fields = property_exists($this->queryString, 'fields') 
                ? explode(',', $this->queryString->fields) 
                : [];

        //! for apartment
        if ($this->modelType === 'apartment') {
            //* default fields
            $defaultFields = ['id', 'title', 'description', 'photos', 'address', 'rent', 'area', 'rating', 'postedAt'];

            /*
            //* allowed fields
            $allowedFields = ['postedBy', 'postedAt','lastUpdatedAt', 'area', 
                                'phoneContact', 'facilities',...$defaultFields];
            // * remove not allowed fields
            foreach ($fields as $index => $value)
                if (!in_array($value, $allowedFields))
                    array_splice($fields, $index, 1);
            if (count($fields) === 0) $fields = $defaultFields;
            */
            $fields = $defaultFields;
        }//! for comment
        else if ($this->modelType === 'comment') {
            //* default fields
            $fields = ['id', 'text', 'commentedBy', 'commentedAt', 'photo'];
        }

        $this->query = $this->query->select($fields);
        return $this;
    }

    /**
     ** Paginate result
     */
    public function paginate() {
        //* pagination info
        $currentPage = property_exists($this->queryString, 'page') ? (int)$this->queryString->page : 1;
        $limit = property_exists($this->queryString, 'limit') ? (int)$this->queryString->limit : 15;
        $skip = ($currentPage - 1) * $limit;

        //* create metadata
        $this->meta = (object)[];
        $this->meta->totalPages = ceil($this->query->count() / $limit);
        $this->meta->currentPage = $currentPage;

        $this->query->skip($skip)->take($limit);

        return $this;
    }

    /**
     ** populate table with user foregin keys 
     */
    public function populate() {
        //* populate related user info
        if ($this->modelType === 'comment') {
            $this->query = $this->query->with(['user' => function($query) {
                $query->select('id', 'name', 'photo');
            }]);
        }  
        else if ($this->modelType === 'apartment') {
            $this->query = $this->query->with(['address']);
            if (property_exists($this->queryString, 'fields') 
                && str_contains($this->queryString->fields, 'postedBy')) {
                $this->query = $this->query->with(['user' => function($query) {
                    $query->select('id', 'name', 'photo');
                }]);
            }
        }
        return $this;
    }

    /**
     ** Execute the query
     */
    public function get() {
        
        return $this->query->get();
    }

    /**
     ** Execute the query and get data includes metadata. Call this only after paginate() got called 
     */
    public function getWithMetadata() {
        //! if paginate is no called before, dont call this method! otherwise, it will return null
        if ($this->meta == null) {
            return null;
        }
        
        //* execute query to get data
        $data = $this->query->get();
   
        if($this->meta->currentPage < 1) 
            $this->meta->currentPage = 1;
        
        //* set pageItems metadata 
        $this->meta->pageItems =
            $this->meta->currentPage < $this->meta->totalPages 
            ? 
            (//* limit or noItems in the last page?
                property_exists($this->queryString, 'limit') ? (int)$this->queryString->limit : 15
            ) 
            : count($data);
            
        // return (object)[
        //     'meta' =>' $this->meta',
        //     'data' => '$data'
        // ];
        return (object)[
            'meta' => $this->meta,
            'data' => $data
        ];
    }
}