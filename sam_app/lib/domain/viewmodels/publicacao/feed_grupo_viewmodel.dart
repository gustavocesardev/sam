import 'package:sam_app/data/repositories/publicacao/feed_repository.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_viewmodel.dart';

class FeedGrupoViewmodel extends FeedViewModel {
  final int idGrupoEstudo;

  FeedGrupoViewmodel({
    required FeedRepository repo,
    required this.idGrupoEstudo,
  }) : super(
          fetchPosts: ({int? idUsuario, int page = 1}) =>
              repo.getFeedGrupoEstudo(idGrupoEstudo: idGrupoEstudo, page: page),
        );
}
