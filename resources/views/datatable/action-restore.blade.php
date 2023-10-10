<form action="{{ route($route . '.destroy', $id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button class="delete btn btn-success btn-sm"><i class="fas fa-trash-restore"></i> Restore</button>
</form>
