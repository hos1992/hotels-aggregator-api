<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class Hotel extends Model
{
    /*
     * best hotels provider
     */
    public static function bestHotels($req)
    {

        $hotelsArr = [
            [
                'hotel' => 'best hotel 1',
                'hotelRate' => 5,
                'hotelFare' => round(100.5555555, 2),
                'roomAmenities' => 'amenity 1,amenity 2,amenity 3,amenity 4',
                'city' => 'AAA',
                'dateFrom' => Carbon::now(),
                'dateTo' => Carbon::now()->addMonth(),
            ],
            [
                'hotel' => 'best hotel 2',
                'hotelRate' => 5,
                'hotelFare' => round(100.5555555, 2),
                'roomAmenities' => 'amenity 1,amenity 2,amenity 3,amenity 4',
                'city' => 'AAA',
                'dateFrom' => Carbon::now(),
                'dateTo' => Carbon::now()->addMonth(),
            ],
            [
                'hotel' => 'best hotel 3',
                'hotelRate' => 3,
                'hotelFare' => round(100.5555555, 2),
                'roomAmenities' => 'amenity 1,amenity 2,amenity 3,amenity 4',
                'city' => 'CCC',
                'dateFrom' => Carbon::now(),
                'dateTo' => Carbon::now()->addMonth(),
            ],
        ];


        // check if the request has keys to filter
        if (!empty($req) && count($req) > 0) {
            $collection = collect($hotelsArr);
            $where_arr = [];


            // check the date from
            if (isset($req['date_from']) && !empty($req['date_from'])) {
                $arr = ['dateFrom', '>=', Carbon::parse($req['date_from'])];
                array_push($where_arr, $arr);
            }

            // check the date to
            if (isset($req['date_to']) && !empty($req['date_to'])) {
                $arr = ['dateTo', '<=', Carbon::parse($req['date_to'])];
                array_push($where_arr, $arr);
            }

            // check the city
            if (isset($req['city']) && !empty($req['city'])) {
                $arr = ['city', '=', $req['city']];
                array_push($where_arr, $arr);
            }


            if (count($where_arr) > 0) {
                foreach ($where_arr as $val) {
                    $data = $collection->where($val[0], $val[1], $val[2]);
                }
                return $data;
            }

        }

        return json_encode($hotelsArr);

    }

    /*
     * top hotels provider
     */
    public static function topHotels($req)
    {
        $hotelsArr = [

            [
                'hotelName' => 'top hotel 1',
                'rate' => 5,
                'price' => round(100.5555555, 2),
                'discount' => round(50.555, 2),
                'amenities' => [
                    'room amenity 1',
                    'room amenity 2',
                    'room amenity 3',
                    'room amenity 4',
                ],
                'city' => 'AUH',
                'fromDate' => Carbon::now(),
                'toDate' => Carbon::now()->addMonth(),
            ],

            [
                'hotelName' => 'top hotel 2',
                'rate' => 5,
                'price' => round(100.5555555, 2),
                'discount' => round(50.555, 2),
                'amenities' => [
                    'room amenity 1',
                    'room amenity 2',
                    'room amenity 3',
                    'room amenity 4',
                ],
                'city' => 'CCC',
                'formDate' => Carbon::now(),
                'toDate' => Carbon::now()->addMonth(),
            ],

            [
                'hotelName' => 'top hotel 3',
                'rate' => 5,
                'price' => round(100.5555555, 2),
                'discount' => round(50.555, 2),
                'amenities' => [
                    'room amenity 1',
                    'room amenity 2',
                    'room amenity 3',
                    'room amenity 4',
                ],
                'city' => 'AAA',
                'fromDate' => Carbon::now(),
                'toDate' => Carbon::now()->addMonth(),
            ],
        ];


        // check if the request has keys to filter
        if (!empty($req) && count($req) > 0) {
            $collection = collect($hotelsArr);
            $where_arr = [];


            // check the date from
            if (isset($req['date_from']) && !empty($req['date_from'])) {
                $arr = ['fromDate', '>=', Carbon::parse($req['date_from'])];
                array_push($where_arr, $arr);
            }

            // check the date to
            if (isset($req['date_to']) && !empty($req['date_to'])) {
                $arr = ['toDate', '<=', Carbon::parse($req['date_to'])];
                array_push($where_arr, $arr);
            }

            // check the city
            if (isset($req['city']) && !empty($req['city'])) {
                $arr = ['city', '=', $req['city']];
                array_push($where_arr, $arr);
            }


            if (count($where_arr) > 0) {
                foreach ($where_arr as $val) {
                    $data = $collection->where($val[0], $val[1], $val[2]);
                }
                return $data;
            }

        }

        return json_encode($hotelsArr);


        return json_encode($hotelsArr);
    }


    /*
     * map best hotels for the aggregator
     */
    public static function mapBestHotels($req)
    {
        $hotels = json_decode(SELF::bestHotels($req), true);

        $maped = array_map(function ($hotel) {
            return [
                'providerName' => 'best hotels',
                'hotelName' => $hotel['hotel'],
                'hotelFare' => $hotel['hotelFare'],
                'hotelRate' => $hotel['hotelRate'],
                'amenities' => explode(',', $hotel['roomAmenities']),
            ];
        }, $hotels);

        return $maped;

    }

    /*
     * map top hotels for the aggregator
     */
    public static function mapTopHotels($req)
    {
        $hotels = json_decode(SELF::TopHotels($req), true);

        $maped = array_map(function ($hotel) {
            return [
                'providerName' => 'top hotels',
                'hotelName' => $hotel['hotelName'],
                'hotelFare' => $hotel['price'],
                'hotelRate' => $hotel['rate'],
                'amenities' => $hotel['amenities'],
            ];
        }, $hotels);

        return $maped;

    }


    /*
     * the aggregator function
     */
    public static  function aggregateHotels($req){

        $bestHotels = collect(SELF::mapBestHotels($req));
        $topHotels = collect(SELF::mapTopHotels($req));

        $merged = $bestHotels->merge($topHotels)->sortByDesc('hotelRate');

        return $merged->values()->all();
    }


}
