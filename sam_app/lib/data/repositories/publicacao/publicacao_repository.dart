import 'package:sam_app/data/models/post_model.dart';
import 'package:sam_app/data/requests/publicacao_request.dart';
import 'package:sam_app/data/services/publicacao/publicacao_service.dart';

class PublicacaoRepository {
  final PublicacaoService service;

  PublicacaoRepository(this.service);

  Future<void> criarPublicacao({
    required String chaveAutor,
    required PublicacaoRequest request,
  }) async {
    await service.criarPublicacao(chaveAutor: chaveAutor, request: request);
  }

  Future<PostModel> getPublicacaoById(
    int idPublicacao,
    int? idGrupoEstudo,
  ) async {
    final data = await service.getPublicacao(
      idPublicacao: idPublicacao,
      idGrupoEstudo: idGrupoEstudo,
    );

    final content = data['content'] as Map<String, dynamic>;
    return PostModel.fromJson(content);
  }

  Future<void> adicionarReacao({
    required String chaveAutor,
    required idPublicacao,
  }) async {
    return await service.addReacao(
      chaveAutor: chaveAutor,
      idPublicacao: idPublicacao,
    );
  }

  Future<void> removerReacao({
    required String chaveAutor,
    required idPublicacao,
  }) async {
    return await service.removerReacao(
      chaveAutor: chaveAutor,
      idPublicacao: idPublicacao,
    );
  }
}
