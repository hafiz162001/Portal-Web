<div>
    <form method="POST" action="{{ route($route.'.destroy', $object->id) }}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        
        <a href="{{ route($route.'.edit', $object->id) }}" class="btn btn-xs btn-primary">Edit</a>
        <button class="btn btn-xs btn-danger">Hapus</button>
    </form>
</div>