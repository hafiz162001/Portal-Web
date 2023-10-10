<?php

namespace App\DataTables;

use App\Models\SyaratKetentuan;
use App\Models\TermCondition;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TermConditionDataTable extends DataTable
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
            ->addColumn('term_condition_detail', function ($query) {
                return view("datatable.detail")->with(['id' => $query->id, 'route' => 'term-condition']);
            })
            ->addColumn('created_at', function ($query) {
                return $query->created_at;
            })
            ->addColumn('updated_at', function ($query) {
                return $query->updated_at;
            })
            ->addColumn('action', function ($query) {
                return view('datatable.action')->with(['id' => $query->id, 'route' => 'term-condition']);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SyaratKetentuan $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SyaratKetentuan $model)
    {
        return $model->where('category', 'blocx')->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('termcondition-table')
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
            Column::make('page_nm')->content('-')->title('Name'),
            Column::make('page_title')->content('-')->title('Title'),
            Column::make('term_condition_detail')->content('-')->title('Term & Condition Detail'),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'TermCondition_' . date('YmdHis');
    }
}
