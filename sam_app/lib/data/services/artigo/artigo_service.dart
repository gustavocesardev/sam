import 'package:sam_app/data/models/artigo_model.dart';
import 'package:sam_app/data/services/http_service.dart';

class ArtigoService {
  final HttpService _http = HttpService();

  Future<List<ArtigoModel>> fetchFiltrados({
    int page = 1,
    Map<String, dynamic>? filtros,
  }) async {
    final response = await _http.post(
      '/artigo-universitario/filtrar?page=$page&limite=7',
      body: filtros,
    );

    final list = response['content'] as List;
    return list.map((e) => ArtigoModel.fromJson(e)).toList();
  }

  Future<List<ArtigoModel>> fetchCriados({int page = 1}) async {
    final response = await _http.get(
      '/artigo-universitario/criados?page=$page&limite=7',
    );

    final list = response['content'] as List;
    return list.map((e) => ArtigoModel.fromJson(e)).toList();
  }
}
