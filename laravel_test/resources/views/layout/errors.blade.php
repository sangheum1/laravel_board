@if($errors->any())
    @foreach ($errors->all() as $val )
        <div style="color:red">{{$val}}</div>
        
    @endforeach
@else
    
@endif