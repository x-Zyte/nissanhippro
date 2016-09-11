<?php
/**
 * @file
 * Repository Interface.
 *
 * All LaravelJqGrid code is copyright by the original authors and released under the MIT License.
 * See LICENSE.
 */

namespace App\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

abstract class EloquentRepositoryAbstract implements RepositoryInterface{


    /**
     * Database
     *
     * @var Illuminate\Database\Eloquent\Model or Illuminate\Database\Query
     *
     */
    protected $Database;

    /**
     * Visible columns
     *
     * @var Array
     *
     */
    protected $visibleColumns;

    /**
     * OrderBy
     *
     * @var array
     *
     */
    protected $orderBy = array(array());


    protected $crudFields;
    protected $uniqueKeySingles;
    protected $uniqueKeyMultiples;
    protected $hasBranch = false;
    protected $hasProvince = false;
    protected $parentHasProvince = false;
    protected $parentModel;
    protected $whereNotNullFields = array();
    
    /**
     * Calculate the number of rows. It's used for paging the result.
     *
     * @param  array $filters
     *  An array of filters, example: array(array('field'=>'column index/name 1','op'=>'operator','data'=>'searched string column 1'), array('field'=>'column index/name 2','op'=>'operator','data'=>'searched string column 2'))
     *  The 'field' key will contain the 'index' column property if is set, otherwise the 'name' column property.
     *  The 'op' key will contain one of the following operators: '=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'is in', 'is not in'.
     *  when the 'operator' is 'like' the 'data' already contains the '%' character in the appropiate position.
     *  The 'data' key will contain the string searched by the user.
     * @return integer
     *  Total number of rows
     */
    public function getTotalNumberOfRows(array $filters = array(), $groupOp = 'AND')
    {
        return  intval($this->Database->whereNested(function($query) use ($filters,$groupOp)
        {
            if($this->hasBranch && !Auth::user()->isadmin){
                $query->where('branchid', Auth::user()->branchid);
            }
            if($this->hasProvince && !Auth::user()->isadmin){
                $query->where('provinceid', Auth::user()->provinceid);
            } else {
                if ($this->parentHasProvince && !Auth::user()->isadmin) {
                    $query->whereHas($this->parentModel, function ($q) {
                        $q->where('provinceid', Auth::user()->provinceid);
                    });
                }
            }

            foreach ($this->whereNotNullFields as $fieldName) {
                $query->whereNotNull($fieldName);
            }

            foreach ($filters as $filter)
            {
                if($groupOp == 'AND') {
                    if ($filter['op'] == 'is in') {
                        $query->whereIn($filter['field'], explode(',', $filter['data']));
                        continue;
                    }

                    if ($filter['op'] == 'is not in') {
                        $query->whereNotIn($filter['field'], explode(',', $filter['data']));
                        continue;
                    }

                    if (strpos($filter['field'], 'date') !== false)
                        $filter['data'] = date('Y-m-d', strtotime($filter['data']));

                    $query->where($filter['field'], $filter['op'], $filter['data']);
                }
                else{
                    if ($filter['op'] == 'is in') {
                        $query->orWhereIn($filter['field'], explode(',', $filter['data']));
                        continue;
                    }

                    if ($filter['op'] == 'is not in') {
                        $query->orWhereNotIn($filter['field'], explode(',', $filter['data']));
                        continue;
                    }

                    if (strpos($filter['field'], 'date') !== false)
                        $filter['data'] = date('Y-m-d', strtotime($filter['data']));

                    $query->orWhere($filter['field'], $filter['op'], $filter['data']);
                }
            }
        })
            ->count());
    }


    /**
     * Get the rows data to be shown in the grid.
     *
     * @param  integer $limit
     *  Number of rows to be shown into the grid
     * @param  integer $offset
     *  Start position
     * @param  string $orderBy
     *  Column name to order by.
     * @param  array $sordvisibleColumns
     *  Sorting order
     * @param  array $filters
     *  An array of filters, example: array(array('field'=>'column index/name 1','op'=>'operator','data'=>'searched string column 1'), array('field'=>'column index/name 2','op'=>'operator','data'=>'searched string column 2'))
     *  The 'field' key will contain the 'index' column property if is set, otherwise the 'name' column property.
     *  The 'op' key will contain one of the following operators: '=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'is in', 'is not in'.
     *  when the 'operator' is 'like' the 'data' already contains the '%' character in the appropiate position.
     *  The 'data' key will contain the string searched by the user.
     * @return array
     *  An array of array, each array will have the data of a row.
     *  Example: array(array("column1" => "1-1", "column2" => "1-2"), array("column1" => "2-1", "column2" => "2-2"))
     */
    public function getRows($limit, $offset, $orderBy = null, $sord = null, array $filters = array(), $groupOp = 'AND')
    {
        $orderByRaw = null;

        if(!is_null($orderBy) || !is_null($sord))
        {
            $found = false;
            $pos = strpos($orderBy, 'desc');

            if ($pos !== false)
            {
                $found = true;
            }
            else
            {
                $pos = strpos($orderBy, 'asc');

                if ($pos !== false)
                {
                    $found = true;
                }
            }

            if($found)
            {
                $orderBy = rtrim($orderBy);

                if(substr($orderBy, -1) == ',')
                {
                    $orderBy = substr($orderBy, 0, -1);
                }
                else
                {
                    $orderBy .= " $sord";
                }

                $orderByRaw = $orderBy;
            }
            else
            {
                $this->orderBy = array(array($orderBy, $sord));
            }
        }

        if($limit == 0)
        {
            $limit = 1;
        }

        if(empty($orderByRaw))
        {
            $orderByRaw = array();

            foreach ($this->orderBy as $orderBy)
            {
                array_push($orderByRaw, implode(' ',$orderBy));
            }

            $orderByRaw = implode(',',$orderByRaw);
        }

        $rows = $this->Database->whereNested(function($query) use ($filters,$groupOp)
        {
            if($this->hasBranch && !Auth::user()->isadmin){
                $query->where('branchid', Auth::user()->branchid);
            }
            if($this->hasProvince && !Auth::user()->isadmin){
                $query->where('provinceid', Auth::user()->provinceid);
            } else {
                if ($this->parentHasProvince && !Auth::user()->isadmin) {
                    $query->whereHas($this->parentModel, function ($q) {
                        $q->where('provinceid', Auth::user()->provinceid);
                    });
                }
            }

            foreach ($this->whereNotNullFields as $fieldName) {
                $query->whereNotNull($fieldName);
            }

            foreach ($filters as $filter)
            {
                if($groupOp == 'AND') {
                    if ($filter['op'] == 'is in') {
                        $query->whereIn($filter['field'], explode(',', $filter['data']));
                        continue;
                    }

                    if ($filter['op'] == 'is not in') {
                        $query->whereNotIn($filter['field'], explode(',', $filter['data']));
                        continue;
                    }

                    if (strpos($filter['field'], 'date') !== false)
                        $filter['data'] = date('Y-m-d', strtotime($filter['data']));

                    $query->where($filter['field'], $filter['op'], $filter['data']);
                }
                else{
                    if ($filter['op'] == 'is in') {
                        $query->orWhereIn($filter['field'], explode(',', $filter['data']));
                        continue;
                    }

                    if ($filter['op'] == 'is not in') {
                        $query->orWhereNotIn($filter['field'], explode(',', $filter['data']));
                        continue;
                    }

                    if (strpos($filter['field'], 'date') !== false)
                        $filter['data'] = date('Y-m-d', strtotime($filter['data']));

                    $query->orWhere($filter['field'], $filter['op'], $filter['data']);
                }
            }
        })
            ->take($limit)
            ->skip($offset)
            ->orderByRaw($orderByRaw)
            ->get($this->visibleColumns);

        if(!is_array($rows))
        {
            $rows = $rows->toArray();
        }

        foreach ($rows as &$row)
        {
            $row = (array) $row;
        }

        return $rows;
    }

    public function crud($postedData){
        $input = $postedData->only($this->crudFields);

        if($input['oper'] == 'add'){
            foreach ($this->uniqueKeySingles as $key)
            {
                if($input[$key['field']] != null || $input[$key['field']] != ''){
                    $countDuplicate = intval($this->Database->where($key['field'], $input[$key['field']])->count());
                    if($countDuplicate > 0){
                        return $key['label'].' '.$input[$key['field']].' มีอยู่ในระบบแล้ว';
                    }
                }
            }

            if(!empty($this->uniqueKeyMultiples))
            {
                $countDuplicate = intval($this->Database->whereNested(function($query) use ($input)
                {
                    foreach ($this->uniqueKeyMultiples as $key)
                    {
                        if($input[$key['field']] != null || $input[$key['field']] != '') {
                            $query->where($key['field'], $input[$key['field']]);
                        }
                    }
                })->count());

                if($countDuplicate > 0){
                    $msg = array();
                    foreach ($this->uniqueKeyMultiples as $key)
                    {
                        if($key['showInMsg'])
                            array_push($msg,$key['label'].' '.$input[$key['field']]);
                    }
                    return implode(",",$msg).' มีอยู่ในระบบแล้ว';
                }
            }

            $this->Database->create($input);
        }
        elseif($input['oper'] == 'edit'){
            foreach ($this->uniqueKeySingles as $key)
            {
                if($input[$key['field']] != null || $input[$key['field']] != '') {
                    $countDuplicate = intval($this->Database->where('id', '!=', $input['id'])->where($key['field'], $input[$key['field']])->count());
                    if ($countDuplicate > 0) {
                        return $key['label'] . ' ' . $input[$key['field']] . ' มีอยู่ในระบบแล้ว';
                    }
                }
            }

            if(!empty($this->uniqueKeyMultiples))
            {
                $countDuplicate = intval($this->Database->whereNested(function($query) use ($input)
                {
                    $query->where('id','!=', $input['id']);
                    foreach ($this->uniqueKeyMultiples as $key)
                    {
                        if($input[$key['field']] != null || $input[$key['field']] != '') {
                            $query->where($key['field'], $input[$key['field']]);
                        }
                    }
                })->count());

                if($countDuplicate > 0){
                    $msg = array();
                    foreach ($this->uniqueKeyMultiples as $key)
                    {
                        if($key['showInMsg'])
                            array_push($msg,$key['label'].' '.$input[$key['field']]);
                    }
                    return implode(",",$msg).' มีอยู่ในระบบแล้ว';
                }
            }

            $this->Database->find($input['id'])->update($input);
        }
        elseif($input['oper'] == 'del') {
            $delIds = explode(',',$input['id']);
            try
            {
                $this->Database->destroy($delIds); //->whereIn('id',$delIds)->delete();
            }
            catch(QueryException $exception)
            {
                return 'รายการที่เลือกถูกใช้งานอยู่ ไม่สามารถลบได้';
            }
        }

        return "ok";
    }
}