<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            // ->filterColumn('location_id', function ($query, $keyword) {
            //     $query->join('locations', 'users.location_id', '=', 'locations.id');
            //     $query->where('locations.name', 'ilike', '%' . $keyword . '%');
            // })
            // ->addColumn('location_id', function ($query) {
            //     return !empty($query->location) ? $query->location->name . " | " . $query->location->type : "-";
            // })
            ->addColumn('action', function ($query) {
                return view('datatable.action')->with(['id' => $query->id, 'route' => 'access-users']);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        if (Auth::user()->bloc_id) {
            return $model->newQuery()->with(['location'])->with(['blocLocation'])->where('bloc_id', Auth::user()->bloc_id);
        }
        return $model->newQuery()->with(['location'])->with(['blocLocation']);
        // return $model->newQuery()->with(['location'])->orWhere('bloc_id', Auth::user()->bloc_id);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->title('No')->data('DT_RowIndex'),
            Column::make('id'),
            Column::make('fullname'),
            Column::make('username'),
            Column::make('email'),
            Column::make('role_code')->title("Role"),
            'bloc_name' => ['title' => __('Bloc'), 'data' => 'bloc_location_name', 'name' => 'blocLocation.name', 'defaultContent' => '-'],
            // Column::make('location_id')->title("Location"),
            'location_name' => ['title' => __('Location'), 'data' => 'location_name', 'name' => 'location.*', 'defaultContent' => '-'],
            Column::computed('action')
                ->exportable(false)
                ->printable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'User_' . date('YmdHis');
    }
}
