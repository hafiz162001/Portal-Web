<?php

namespace App\DataTables;

use App\Models\Article;
use App\Models\ArticleDetail;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ArticleDataTable extends DataTable
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
            ->addColumn('cover', function ($query) {
                if (empty($query->gambar)) {
                    return "-";
                }
                return view('datatable.gambar')->with(['imgName' => $query->gambar]);
            })
            ->addColumn('category', function ($query) {
                return $query->category ? $query->category['name'] : '-' ;
            })
            ->addColumn('channel', function ($query) {
                return $query->channel ? $query->channel['name'] : '-'; 
            })
            ->addColumn('created_at', function ($query) {
                return $query->created_at;
            })
            ->addColumn('updated_at', function ($query) {
                return $query->updated_at;
            })
            ->addColumn('action', function ($query) {
                return view('datatable.action')->with(['id' => $query->id, 'route' => 'article']);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Article $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Article $model)
    {
        $category = $this->request()->get('category');
        $query = $model->newQuery();

        if (!is_null($category)) {
            $query = $query->where('category', $category);
        }

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('article-table')
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
            Column::computed('cover')->orderable(false),
            Column::make('title'),
            Column::make('slug'),
            Column::make('publisher'),
            Column::make('category'),
            Column::make('channel'),
            // Column::computed('article_details')->title('Article Detail'),
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
        return 'Article_' . date('YmdHis');
    }
}
