<?php

namespace App\DataTables;

use App\Models\ChannelArticle;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ChannelArticleDataTable extends DataTable
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
            ->addColumn('image', function ($query) {
                return view('datatable.gambar')->with(['imgName' => $query->image]);
            })
            ->addColumn('parent', function ($query) {

                return $query->parent?->name;
            })
            ->addColumn('action', function ($query) {
                return view('datatable.action')->with(['id' => $query->id, 'route' => 'channel-article']);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CategoryLocation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ChannelArticle $model)
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
            ->setTableId('ChannelArticle-table')
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
            Column::computed('image'),
            Column::make('name'),
            Column::make('slug'),
            Column::computed('parent'),
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
        return 'ChannelArticle_' . date('YmdHis');
    }
}
