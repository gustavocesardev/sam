import 'package:sam_app/data/repositories/publicacao/feed_repository.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_viewmodel.dart';

class FeedCurtidasViewmodel extends FeedViewModel {
  FeedCurtidasViewmodel(FeedRepository repo, int idUsuario)
    : super(
        fetchPosts: ({int page = 1}) {
          return repo.getCurtidas(page: page, idUsuario: idUsuario);
        },
      );
}
