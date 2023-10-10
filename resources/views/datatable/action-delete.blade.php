<form action="{{ route($route . '.destroy', $id) }}" method="POST" onsubmit="deleteData(event);">
    @csrf
    @method('DELETE')
    <button class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
</form>
