import 'package:sam_app/data/models/artigo_model.dart';
import 'package:sam_app/data/requests/artigo_request.dart';
import 'package:sam_app/data/services/artigo/artigo_service.dart';

class ArtigoRepository {
  final ArtigoService service = ArtigoService();

  Future<List<ArtigoModel>> getFiltrados({
    int page = 1,
    Map<String, dynamic>? filtros,
  }) {
    return service.fetchFiltrados(page: page, filtros: filtros);
  }

  Future<List<ArtigoModel>> getCriados({int page = 1}) {
    return service.fetchCriados(page: page);
  }

  Future<ArtigoModel> index(int id) {
    return service.index(id: id);
  }

  Future<void> store({required ArtigoRequest request}) async {
    await service.store(request: request);
  }

  Future<void> delete({required int id}) {
    return service.delete(id: id);
  }
}
