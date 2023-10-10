<form method="POST" action="{{ route($route.'.destroy', $data->id) }}">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    
    <a href="{{ route($route.'.edit', $data->id) }}" class="btn btn-xs btn-primary">Edit</a>
    <button class="btn btn-xs btn-delete btn-danger">Hapus</button>
</form>