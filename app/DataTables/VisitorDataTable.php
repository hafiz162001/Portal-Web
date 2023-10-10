<?php

namespace App\DataTables;

use App\Models\Visitor;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class VisitorDataTable extends DataTable
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
            ->filterColumn('created_by', function ($query, $keyword) {
                $query->join('user_apps', 'visitors.created_by', '=', 'user_apps.id');
                $query->where('user_apps.name', 'ilike', '%' . $keyword . '%');
            })
            ->addColumn('action', 'visitor.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Visitor $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Visitor $model)
    {
        return $model->join('user_apps', 'visitors.created_by', '=', 'user_apps.id')->select(['visitors.*', 'user_apps.name as username'])->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('visitor-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
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
            Column::make('title'),
            Column::make('phone'),
            Column::make('name'),
            Column::make('email'),
            Column::make('ktp'),
            Column::make('created_by')->data('username')->content('username'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Visitor_' . date('YmdHis');
    }
}
