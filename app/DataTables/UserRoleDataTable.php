<?php

namespace App\DataTables;

use App\Models\UserRole;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserRoleDataTable extends DataTable
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
                return view('datatable.action')->with(['id' => $query->id, 'route' => 'role']);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\UserRole $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(UserRole $model)
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
            ->setTableId('userrole-table')
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
            Column::make('code'),
            Column::make('view'),
            Column::make('create'),
            Column::make('update'),
            Column::make('delete'),
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
        return 'UserRole_' . date('YmdHis');
    }
}
