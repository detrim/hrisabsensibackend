@if ($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@elseif(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div id="notif" class="alert d-none position-fixed top-0 end-0 m-3 shadow" style="z-index:9999; min-width:250px;">
</div>
