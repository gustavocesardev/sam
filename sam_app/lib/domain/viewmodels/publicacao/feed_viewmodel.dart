import 'package:flutter/material.dart';
import 'package:sam_app/data/models/post_model.dart';

typedef FeedFetcher = Future<List<PostModel>> Function({required int page});

class FeedViewModel extends ChangeNotifier {
  final Future<List<PostModel>> Function({int page}) fetchPosts;

  FeedViewModel({required this.fetchPosts});

  List<PostModel> _posts = [];
  List<PostModel> get posts => _posts;

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  int _page = 1;
  bool _hasMore = true;

  Future<void> loadInitial() async {
    _page = 1;
    _posts = [];
    _hasMore = true;
    await loadMore();
  }

  Future<void> loadMore() async {
    if (_isLoading || !_hasMore) return;

    _isLoading = true;
    notifyListeners();

    final newPosts = await fetchPosts(page: _page);
    _posts.addAll(newPosts);
    _hasMore = newPosts.isNotEmpty;
    _page++;

    _isLoading = false;
    notifyListeners();
  }

  bool get hasMore => _hasMore;
}