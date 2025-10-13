import 'package:sam_app/data/repositories/publicacao/feed_repository.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_viewmodel.dart';

class FeedCursoViewmodel extends FeedViewModel {
  FeedCursoViewmodel(FeedRepository repo)
      : super(fetchPosts: ({int? idUsuario, int page = 1}) => repo.getFeedCurso(page: page));
}
