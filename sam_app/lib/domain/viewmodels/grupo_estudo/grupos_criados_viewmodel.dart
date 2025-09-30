import 'package:sam_app/data/repositories/grupo_estudo/grupos_estudo_repository.dart';
import 'package:sam_app/domain/viewmodels/grupo_estudo/grupos_estudo_viewmodel.dart';

class GruposCriadosViewmodel extends GruposEstudoViewmodel {
  GruposCriadosViewmodel(GruposEstudoRepository repo)
    : super(fetchGrupos: ({int page = 1}) => repo.getCriados(page: page));
}
