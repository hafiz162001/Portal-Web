<ol>
    @foreach ($articleDetails as $articleDetail)
        <li>
            <strong>Description:</strong> "{{ $articleDetail->description }}" <br>
            <strong> Caption:</strong> "{{ $articleDetail->caption }}"<br>
            <strong> Status:</strong> "{{ $articleDetail->status === 0 ? 'Unpublished' : 'Published' }}"<br>
        </li>
    @endforeach
</ol>
