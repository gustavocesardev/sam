import 'package:sam_app/data/models/grupo_estudo_model.dart';
import 'package:sam_app/data/services/grupo_estudo/grupo_estudo_service.dart';

class GruposEstudoRepository {
  final GrupoEstudoService service = GrupoEstudoService();

  Future<List<GrupoEstudoModel>> getIngressados({int page = 1}) {
    return service.fetchIngressados(page: page);
  }

  Future<List<GrupoEstudoModel>> getCriados({int page = 1}) {
    return service.fetchCriador(page: page);
  }

  Future<List<GrupoEstudoModel>> getPopulares({int page = 1}) {
    return service.fetchPopulares(page: page);
  }
}
