import 'package:flutter/material.dart';
import 'package:sam_app/data/models/grupo_estudo_model.dart';

typedef GrupoFetcher =
    Future<List<GrupoEstudoModel>> Function({required int page});

class GruposEstudoViewmodel extends ChangeNotifier {
  final GrupoFetcher fetchGrupos;

  GruposEstudoViewmodel({required this.fetchGrupos});

  List<GrupoEstudoModel> _grupos = [];
  List<GrupoEstudoModel> get grupos => _grupos;

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  bool _isLoadingInitial = false;
  bool get isLoadingInitial => _isLoadingInitial;

  int _page = 1;
  bool _hasMore = true;

  Future<void> loadInitial() async {
    _page = 1;
    _grupos = [];
    _hasMore = true;

    _isLoadingInitial = true;
    notifyListeners();

    await loadMore();

    _isLoadingInitial = false;
    notifyListeners();
  }

  Future<void> loadMore() async {
    if (_isLoading || !_hasMore) return;

    _isLoading = true;
    notifyListeners();

    final newGrupos = await fetchGrupos(page: _page);
    _grupos.addAll(newGrupos);
    _hasMore = newGrupos.isNotEmpty;
    _page++;

    _isLoading = false;
    notifyListeners();
  }

  bool get hasMore => _hasMore;
}
