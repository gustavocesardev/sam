import 'package:sam_app/data/repositories/grupo_estudo/grupos_estudo_repository.dart';
import 'package:sam_app/domain/viewmodels/grupo_estudo/grupos_estudo_viewmodel.dart';

class GruposIngressadosViewmodel extends GruposEstudoViewmodel {
  GruposIngressadosViewmodel(GruposEstudoRepository repo)
    : super(fetchGrupos: ({int page = 1}) => repo.getIngressados(page: page));
}
