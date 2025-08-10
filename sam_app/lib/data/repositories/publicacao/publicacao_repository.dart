import 'package:sam_app/data/requests/publicacao_request.dart';
import 'package:sam_app/data/services/publicacao/publicacao_service.dart';

class PublicacaoRepository {
  final PublicacaoService service;

  PublicacaoRepository(this.service);

  Future<void> criarPublicacao({
    required String chaveAutor,
    required PublicacaoRequest request,
  }) async {

    await service.criarPublicacao(
      chaveAutor: chaveAutor,
      request: request,
    );
  }
}
