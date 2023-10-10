<form action="{{ route($route . '.destroy', $id) }}" method="POST" onsubmit="deleteData(event);">
    @csrf
    @method('DELETE')
    {{-- <a href="{{ route('promo-code.index', ['promo_id' => $id]) }}" class="edit btn btn-success btn-sm"><i class="fa fa-eye"></i></a> --}}
    <a href="{{ route($route . '.edit', $id) }}" class="edit btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
    <button class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
</form>
