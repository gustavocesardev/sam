import 'package:http/http.dart' as http;
import 'package:sam_app/data/models/grupo_estudo_model.dart';
import 'package:sam_app/data/models/membro_model.dart';
import 'package:sam_app/data/requests/grupo_estudo_request.dart';
import 'package:sam_app/data/services/http_service.dart';

class GrupoEstudoService {
  final HttpService _http = HttpService();

  Future<GrupoEstudoModel> index({required int id}) async {
    final response = await _http.get('/grupo-estudo/$id');

    final json = response['content'];
    return GrupoEstudoModel.fromJson(json);
  }

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

  Future<void> store({required GrupoEstudoRequest request}) async {
    final fields = <String, String>{
      'id_curso': request.idCurso.toString(),
      'id_usuario': request.idUsuario.toString(),
      'nome_grupo': request.nomeGrupo,
      'descricao': request.descricao,
      'hashtags': request.hashtags,
    };

    final List<http.MultipartFile> arquivos = [];

    final image = request.imagem;
    final fileName = image.path.split('/').last;
    arquivos.add(
      await http.MultipartFile.fromPath(
        'imagem',
        image.path,
        filename: fileName,
      ),
    );

    final imageHeader = request.imagemHeader;
    final headerFileName = imageHeader.path.split('/').last;
    arquivos.add(
      await http.MultipartFile.fromPath(
        'imagem_header',
        imageHeader.path,
        filename: headerFileName,
      ),
    );

    String endpoint = '/grupo-estudo';

    if (request.idGrupoEstudo != null) {
      fields['_method'] = 'PUT';
      endpoint = '/grupo-estudo/${request.idGrupoEstudo}';
    }

    await _http.postMultipart(
      endpoint: endpoint,
      fields: fields,
      files: arquivos,
    );
  }

  Future<void> delete({required int id}) async {
    await _http.delete('/grupo-estudo/$id');
  }

  Future<List<MembroModel>> fetchMembros({required int idGrupo}) async {
    final response = await _http.get('/grupo-estudo/$idGrupo/membros');
    final list = response['content'] as List;
    return list.map((e) => MembroModel.fromJson(e)).toList();
  }

  Future<MembroModel> ingressarMembro({
    required int idUsuario,
    required int idGrupoEstudo,
  }) async {
    final body = {
      'id_usuario': idUsuario,
      'id_grupo_estudo': idGrupoEstudo,
      'situacao': 'A',
    };

    final response = await _http.post('/grupo-estudo/membro', body: body);
    final content = response['content'] as Map<String, dynamic>;

    return MembroModel.fromJson(content);
  }

  Future<void> removerMembro({
    required int idMembro,
    required int idUsuario,
    required int idGrupoEstudo,
  }) async {
    final body = {
      'id_usuario': idUsuario,
      'id_grupo_estudo': idGrupoEstudo,
      'situacao': 'I',
    };

    await _http.post('/grupo-estudo/membro', body: body);
  }
}
