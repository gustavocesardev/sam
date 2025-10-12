import 'package:flutter/material.dart';
import 'package:sam_app/data/models/post_model.dart';

typedef FeedFetcher = Future<List<PostModel>> Function({required int page});

class FeedViewModel extends ChangeNotifier {
  final FeedFetcher fetchPosts;

  FeedViewModel({required this.fetchPosts});

  final List<PostModel> _posts = [];
  List<PostModel> get posts => _posts;

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  int _page = 1;
  bool _hasMore = true;
  bool get hasMore => _hasMore;

  Future<void> loadInitial() async {
    if (_isLoading) return;

    _page = 1;
    _hasMore = true;
    _posts.clear();

    _isLoading = true;
    notifyListeners();

    final newPosts = await fetchPosts(page: _page);
    _posts.addAll(newPosts);
    _hasMore = newPosts.isNotEmpty;
    _page++;

    _isLoading = false;
    notifyListeners();
  }

  Future<void> loadMore() async {
    if (_isLoading || !_hasMore) return;

    _isLoading = true;
    notifyListeners();

    final newPosts = await fetchPosts(page: _page);

    if (newPosts.isEmpty) {
      _hasMore = false;
    } else {
      _posts.addAll(newPosts);
      _page++;
    }

    _isLoading = false;
    notifyListeners();
  }
}
