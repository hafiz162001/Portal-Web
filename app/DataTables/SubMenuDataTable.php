<?php

namespace App\DataTables;

use App\Models\SubMenu;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SubMenuDataTable extends DataTable
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
                return view('datatable.action')->with(['id' => $query->id, 'route' => 'sub-menu']);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SubMenu $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SubMenu $model)
    {
        return $model->newQuery()->with("menu");
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('submenu-table')
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
            Column::make('statusName', 'status')->title("Status"),
            Column::make('menu.name')->title("Menu"),
            Column::make('path'),
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
        return 'SubMenu_' . date('YmdHis');
    }
}
