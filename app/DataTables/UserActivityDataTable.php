<?php

namespace App\DataTables;

use App\Models\UserActivity;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserActivityDataTable extends DataTable
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
            ->filterColumn('checkin_at', function ($query, $keyword) {
                $query->where('user_activities.checkin_at', 'ilike', '%' . $keyword . '%');
            })
            ->filterColumn('checkout_at', function ($query, $keyword) {
                $query->where('user_activities.checkout_at', 'ilike', '%' . $keyword . '%');
            })
            ->addColumn('checkin_at', function ($query) {
                return $query->checkin_at;
            })
            ->addColumn('checkout_at', function ($query) {
                return $query->checkout_at;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\UserActivity $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(UserActivity $model)
    {
        return $model->with(['blocLocation', 'user'])->whereRelation('user', 'user_category', '=', 'blocx')->orWhereRelation('user', 'user_category', '=', null)->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('useractivity-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->buttons(
                Button::make('create'),
                Button::make('export'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            );
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
            'user_name' => ['title' => __('User Name'), 'data' => 'user_name', 'name' => 'user.name', 'defaultContent' => '-'],
            'location' => ['title' => __('Location'), 'data' => 'location_name', 'name' => 'blocLocation.name', 'defaultContent' => '-'],
            Column::make('checkin_at')->content('-'),
            Column::make('checkout_at')->content('-'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'UserActivity_' . date('YmdHis');
    }
}
