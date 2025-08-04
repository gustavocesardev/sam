import 'package:sam_app/data/repositories/grupo_estudo/grupos_estudo_repository.dart';
import 'package:sam_app/domain/viewmodels/grupo_estudo/grupos_estudo_viewmodel.dart';

class GruposPopularesViewmodel extends GruposEstudoViewmodel {
  GruposPopularesViewmodel(GruposEstudoRepository repo)
    : super(fetchGrupos: ({int page = 1}) => repo.getPopulares(page: page));
}
