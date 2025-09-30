import 'package:sam_app/data/repositories/publicacao/feed_repository.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_viewmodel.dart';

class FeedGeralViewmodel extends FeedViewModel {
  FeedGeralViewmodel(FeedRepository repo)
      : super(fetchPosts: ({int page = 1}) => repo.getFeed(page: page));
}
