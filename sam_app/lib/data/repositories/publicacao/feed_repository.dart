import 'package:sam_app/data/models/post_model.dart';
import 'package:sam_app/data/services/publicacao/feed_service.dart';

class FeedRepository {
  final FeedService service = FeedService();

  Future<List<PostModel>> getFeed({int page = 1}) {
    return service.fetchFeed(page: page);
  }

  Future<List<PostModel>> getFeedCurso({int page = 1}) {
    return service.fetchFeedCurso(page: page);
  }

  Future<List<PostModel>> getFeedGrupoEstudo({required int idGrupoEstudo, int page = 1}) {
    return service.fetchFeedGrupoEstudo(idGrupoEstudo: idGrupoEstudo, page: page);
  }
}
