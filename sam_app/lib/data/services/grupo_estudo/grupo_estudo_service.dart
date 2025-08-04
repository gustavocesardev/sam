import 'package:sam_app/data/models/grupo_estudo_model.dart';
import 'package:sam_app/data/services/http_service.dart';

class GrupoEstudoService {
  final HttpService _http = HttpService();

  Future<List<GrupoEstudoModel>> fetchIngressados({int page = 1}) async {
    final response = await _http.get(
      '/grupo-estudo/grupos/ingressados?page=$page&limite=7',
    );

    final list = response['content'] as List;
    return list.map((e) => GrupoEstudoModel.fromJson(e)).toList();
  }

  Future<List<GrupoEstudoModel>> fetchCriador({int page = 1}) async {
    final response = await _http.get(
      '/grupo-estudo/grupos/criador?page=$page&limite=7',
    );

    final list = response['content'] as List;
    return list.map((e) => GrupoEstudoModel.fromJson(e)).toList();    
  }

  Future<List<GrupoEstudoModel>> fetchPopulares({int page = 1}) async {
    final response = await _http.get(
      '/grupo-estudo/grupos/populares?page=$page&limite=7',
    );

    final list = response['content'] as List;
    return list.map((e) => GrupoEstudoModel.fromJson(e)).toList(); 
  }
}
