import 'package:sam_app/data/enums/tipo_autor_publicacao.dart';
import 'package:sam_app/data/models/post_model.dart';
import 'package:sam_app/data/repositories/publicacao/feed_repository.dart';
import 'package:sam_app/data/repositories/publicacao/publicacao_repository.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_viewmodel.dart';

class PostViewmodel extends FeedViewModel {
  final int idPublicacao;
  final int idUsuario;
  final int? idGrupoEstudo;
  final int idAutor;
  final TipoAutorPublicacao tipoAutorPublicacao;

  final PublicacaoRepository publicacaoRepo;

  late PostModel publicacao;
  bool isLoadingDetalhe = true;

  PostViewmodel({
    required FeedRepository feedRepo,
    required this.publicacaoRepo,
    required this.idPublicacao,
    required this.idUsuario,
    required this.idGrupoEstudo,
    required this.idAutor,
    required this.tipoAutorPublicacao
  }) : super(
          fetchPosts: ({int page = 1}) => feedRepo.getVinculadas(
            idPublicacao: idPublicacao,
            idGrupoEstudo: idGrupoEstudo,
            page: page,
          ),
        );

  Future<void> loadPublicacao() async {
    isLoadingDetalhe = true;
    notifyListeners();

    publicacao = await publicacaoRepo.getPublicacaoById(idPublicacao, idGrupoEstudo);

    isLoadingDetalhe = false;
    notifyListeners();
  }
}
