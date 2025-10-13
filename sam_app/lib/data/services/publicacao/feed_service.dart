import 'package:sam_app/data/models/post_model.dart';
import 'package:sam_app/data/services/http_service.dart';

class FeedService {
  final HttpService _http = HttpService();

  Future<List<PostModel>> fetchFeed({int page = 1}) async {
    final response = await _http.get('/feed?page=$page&limite=7');

    final list = response['content'] as List;
    return list.map((e) => PostModel.fromJson(e)).toList();
  }

  Future<List<PostModel>> fetchFeedCurso({int page = 1}) async {
    final response = await _http.get('/feed/curso?page=$page&limite=7');

    final list = response['content'] as List;
    return list.map((e) => PostModel.fromJson(e)).toList();
  }

  Future<List<PostModel>> fetchFeedGrupoEstudo({
    required int idGrupoEstudo,
    int page = 1,
  }) async {
    final response = await _http.get(
      '/feed/grupo-estudo/$idGrupoEstudo?page=$page&limite=7',
    );

    final list = response['content'] as List;
    return list.map((e) => PostModel.fromJson(e)).toList();
  }

  Future<List<PostModel>> fetchVinculadas({
    required int idPublicacao,
    int? idGrupoEstudo,
    int page = 1,
  }) async {
    String endpoint =
        '/publicacao/$idPublicacao/vinculadas?page=$page&limite=7';

    if (idGrupoEstudo != null) {
      endpoint =
          '/grupo-estudo/$idGrupoEstudo/publicacao/$idPublicacao/vinculadas?page=$page&limite=7';
    }

    final response = await _http.get(endpoint);

    final list = response['content'] as List;
    return list.map((e) => PostModel.fromJson(e)).toList();
  }

  Future<List<PostModel>> fetchFeedCurtidas({
    required int idUsuario,
    int page = 1,
  }) async {
    String endpoint = '/feed/usuario/$idUsuario/curtidas?page=$page&limite=7';

    final response = await _http.get(endpoint);

    final list = response['content'] as List;
    return list.map((e) => PostModel.fromJson(e)).toList();
  }

  Future<List<PostModel>> fetchFeedUsuario({
    required int idUsuario,
    int page = 1,
  }) async {
    String endpoint = '/feed/usuario/$idUsuario?page=$page&limite=7';

    final response = await _http.get(endpoint);

    final list = response['content'] as List;
    return list.map((e) => PostModel.fromJson(e)).toList();
  }
}
