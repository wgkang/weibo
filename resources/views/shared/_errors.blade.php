@if(count($errors)>0)
    <div class="alert alert-danger">
        <ul>
            @foreach( $errors->all() as $_error )
                <li>{{ $_error }}</li>
            @endforeach
        </ul>
    </div>
@endif