import 'package:sam_app/data/models/formulario_model.dart';
import 'package:sam_app/data/requests/formulario_request.dart';
import 'package:sam_app/data/services/http_service.dart';

class FormularioService {
  final HttpService _http = HttpService();

  Future<FormularioModel> index({required int id}) async {
    final response = await _http.get('/formulario/$id');

    final json = response['content'];
    return FormularioModel.fromJson(json);
  }

  Future<List<FormularioModel>> fetchFiltrados({
    int page = 1,
    Map<String, dynamic>? filtros,
  }) async {
    final response = await _http.post(
      '/formulario/filtrar?page=$page&limite=7',
      body: filtros,
    );

    final list = response['content'] as List;
    return list.map((e) => FormularioModel.fromJson(e)).toList();
  }

  Future<List<FormularioModel>> fetchCriados({int page = 1}) async {
    final response = await _http.get('/formulario/criados?page=$page&limite=7');

    final list = response['content'] as List;
    return list.map((e) => FormularioModel.fromJson(e)).toList();
  }

  Future<void> store({required FormularioRequest request}) async {
    final fields = <String, dynamic>{
      'id_usuario': request.idUsuario,
      'titulo': request.titulo,
      'descricao': request.descricao,
      'tipo': request.tipo,
      'link_forms': request.linkForms,
      'data_limite': request.dataLimite,
    };

    if (request.idFormulario != null) {
      await _http.put('/formulario/${request.idFormulario}', body: fields);
      return;
    }

    await _http.post('/formulario', body: fields);
  }

  Future<void> delete({required int id}) async {
    await _http.delete('/formulario/$id');
  }
}
