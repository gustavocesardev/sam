import 'package:sam_app/data/models/artigo_model.dart';
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
}
