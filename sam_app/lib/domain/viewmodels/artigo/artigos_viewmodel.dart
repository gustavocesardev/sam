import 'package:flutter/material.dart';
import 'package:sam_app/data/models/artigo_model.dart';

typedef ArtigoFetcher = Future<List<ArtigoModel>> Function({
  required int page,
  required Map<String, dynamic>? filters,
});

class ArtigosViewmodel extends ChangeNotifier {
  final ArtigoFetcher fetchArtigos;

  ArtigosViewmodel({required this.fetchArtigos});

  List<ArtigoModel> _artigos = [];
  List<ArtigoModel> get artigos => _artigos;

  bool _isLoadingInitial = false;
  bool get isLoadingInitial => _isLoadingInitial;

  bool _isLoadingMore = false;
  bool get isLoadingMore => _isLoadingMore;

  int _page = 1;
  bool _hasMore = true;

  Map<String, dynamic> _filters = {};

  Future<void> applyFilters(Map<String, dynamic> newFilters) async {
    _filters = newFilters;
    await loadInitial();
  }

  Future<void> loadInitial() async {
    _page = 1;
    _artigos = [];
    _hasMore = true;
    
    _isLoadingInitial = true;
    notifyListeners();

    final newArtigos = await fetchArtigos(page: _page, filters: _filters);

    _artigos = newArtigos;
    _hasMore = newArtigos.isNotEmpty;
    _page++;

    _isLoadingInitial = false;
    notifyListeners();
  }

  Future<void> loadMore() async {
    if (_isLoadingMore || !_hasMore) return;

    _isLoadingMore = true;
    notifyListeners();

    final newArtigos = await fetchArtigos(page: _page, filters: _filters);

    _artigos.addAll(newArtigos);
    _hasMore = newArtigos.isNotEmpty;
    _page++;

    _isLoadingMore = false;
    notifyListeners();
  }

  bool get hasMore => _hasMore;

  bool get isLoading => isLoadingInitial || isLoadingMore;

  void resetPagination() {
    _artigos = [];
    _filters = {};
    _page = 1;
    _hasMore = true;
  }
}