import 'package:sam_app/data/models/curso_model.dart';
import 'package:sam_app/data/services/http_service.dart';

class CursoService {
  final HttpService _http = HttpService();

  Future<List<CursoModel>> getCursosPorInstituicao({
    required int idInstituicao,
  }) async {
    final response = await _http.get('/curso/instituicao/$idInstituicao');

    final list = response['content'] as List;
    return list.map((e) => CursoModel.fromJson(e)).toList();
  }
}
