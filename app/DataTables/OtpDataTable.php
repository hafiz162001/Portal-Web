<?php

namespace App\DataTables;

use App\Models\OtpUserApps;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OtpDataTable extends DataTable
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
            ->addColumn('created_at', function ($query) {
                return $query->created_at;
            })
            ->addColumn('updated_at', function ($query) {
                return $query->updated_at;
            });
        // ->addColumn('action', function ($query) {
        //     return view('datatable.action')->with(['id' => $query->id, 'route' => 'otp']);
        // });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\OtpUserApps $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(OtpUserApps $model)
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
            ->setTableId('otp-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
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
            Column::make('phone')->content('-'),
            Column::make('email')->content('-'),
            Column::make('code')->content('-'),
            Column::make('message')->content('-'),
            Column::make('response')->content('-'),
            Column::make('isUsed')->content('-')->title('isUsed'),
            Column::make('created_at'),
            Column::make('updated_at'),
            // Column::computed('action')
            //     ->exportable(false)
            //     ->printable(false)
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Otp_' . date('YmdHis');
    }
}
