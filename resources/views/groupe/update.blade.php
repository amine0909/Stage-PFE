@extends('../layouts/app')
<!-- Enseignat should connect to his account to set the field "updated BY"-->
@section("content")
  <div class="container">
    <div class="row">
    <a href= "{{ URL::previous() }}">
        <button class="btn btn-default" style="width:100px">Precedent</button>
    </a></div>
    <div class="form_update col-lg-6 col-lg-offset-3">
      <div class="text-center">
        <h2>Modifier une classe</h2>
      </div>
      <form action="{{ route('saveUpdateGroup')}}" method="POST" id="saveUpGroup" >
          {{ csrf_field() }}
          <input type="hidden" id="id_group" value="{{ $group->id}}">
        <div class="form-group" id="Nom_up_err">
            <label>Nom de la classe :</label>
            <input type="text" name="name" value="{{$group->name}}" class="form-control" id="nom_update">
            <span class="text-danger" id="nom_span_err"></span>
        </div>
        <div class="form-group">
            <label>Groupe</label>
            <input type="text" value="{{ $stream }}" class="form-control" name="stream" id="stream_update" disabled>
        </div>
        <small class="text-danger danger">Tout les champs sont obligatoires</small>
        <div class="form-group">
          <a href="save_updated_group">
            <input type="submit" class="btn btn-primary btn-group-justified" value="Enregistrer les modifications" id="save_group"></button>
          </a>
        </div>
      </form>
    </div>
  </div>
  <script>

  </script>
@endsection
