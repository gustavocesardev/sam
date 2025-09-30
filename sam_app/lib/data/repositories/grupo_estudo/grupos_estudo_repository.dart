import 'package:sam_app/data/models/grupo_estudo_model.dart';
import 'package:sam_app/data/models/membro_model.dart';
import 'package:sam_app/data/requests/grupo_estudo_request.dart';
import 'package:sam_app/data/services/grupo_estudo/grupo_estudo_service.dart';

class GruposEstudoRepository {
  final GrupoEstudoService service = GrupoEstudoService();

  Future<GrupoEstudoModel> index({required int id}) {
    return service.index(id: id);
  }

  Future<List<GrupoEstudoModel>> getIngressados({int page = 1}) {
    return service.fetchIngressados(page: page);
  }

  Future<List<GrupoEstudoModel>> getCriados({int page = 1}) {
    return service.fetchCriador(page: page);
  }

  Future<List<GrupoEstudoModel>> getPopulares({int page = 1}) {
    return service.fetchPopulares(page: page);
  }

  Future<void> store({required GrupoEstudoRequest request}) async {
    await service.store(request: request);
  }

  Future<void> delete({required int id}) {
    return service.delete(id: id);
  }

  Future<List<MembroModel>> getMembros({required int idGrupo}) {
    return service.fetchMembros(idGrupo: idGrupo);
  }

  Future<MembroModel> ingressarMembro({
    required int idUsuario,
    required int idGrupoEstudo,
  }) {
    return service.ingressarMembro(
      idUsuario: idUsuario,
      idGrupoEstudo: idGrupoEstudo,
    );
  }

  Future<void> removerMembro({
    required int idMembro,
    required int idUsuario,
    required int idGrupoEstudo,
  }) {
    return service.removerMembro(
      idMembro: idMembro,
      idUsuario: idUsuario,
      idGrupoEstudo: idGrupoEstudo,
    );
  }
}
