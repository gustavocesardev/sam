import 'package:sam_app/data/models/formulario_model.dart';
import 'package:sam_app/data/services/http_service.dart';

class FormularioService {
  final HttpService _http = HttpService();

  Future<List<FormularioModel>> fetchFiltrados({int page = 1, Map<String, dynamic>? filtros}) async {
    final response = await _http.post(
      '/formulario/filtrar?page=$page&limite=7', 
      body: filtros
    );

    final list = response['content'] as List;
    return list.map((e) => FormularioModel.fromJson(e)).toList();
  }

  Future<List<FormularioModel>> fetchCriados({int page = 1}) async {
    final response = await _http.get('/formulario/criados?page=$page&limite=7',);

    final list = response['content'] as List;
    return list.map((e) => FormularioModel.fromJson(e)).toList();
  }
}
