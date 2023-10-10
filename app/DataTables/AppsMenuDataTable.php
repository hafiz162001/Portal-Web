<?php

namespace App\DataTables;

use App\Models\AppsMenu;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AppsMenuDataTable extends DataTable
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
            ->addColumn('file', function ($query) {
                return view('datatable.gambar')->with(['imgName' => $query->file]);
            })
            ->addColumn('action', function ($query) {
                return view('datatable.action')->with(['id' => $query->id, 'route' => 'apps-menu']);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AppsMenu $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AppsMenu $model)
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
            ->setTableId('appsmenu-table')
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
            Column::computed('file'),
            Column::make('name'),
            Column::make('navigate'),
            Column::make('order'),
            Column::make('code'),
            Column::make('category')->addClass('text-capitalize')->content('-'),
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
        return 'AppsMenu_' . date('YmdHis');
    }
}
