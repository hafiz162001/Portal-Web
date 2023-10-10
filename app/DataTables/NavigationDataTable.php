<?php

namespace App\DataTables;

use App\Models\Navigation;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class NavigationDataTable extends DataTable
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
            ->addColumn('action', function ($query) {
                return view('datatable.action')->with(['id' => $query->id, 'route' => 'navi']);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Navigation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Navigation $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('navigation-table')
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
            Column::make('name'),
            Column::make('code'),
            Column::make('order'),
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
        return 'Navigation_' . date('YmdHis');
    }
}
