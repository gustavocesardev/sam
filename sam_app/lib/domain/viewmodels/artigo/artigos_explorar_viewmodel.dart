import 'dart:async';

import 'package:sam_app/data/repositories/artigo/artigo_repository.dart';
import 'package:sam_app/domain/viewmodels/artigo/artigos_viewmodel.dart';

class ArtigosExplorarViewmodel extends ArtigosViewmodel {
  ArtigosExplorarViewmodel(ArtigoRepository repo)
    : super(
        fetchArtigos:
            ({required int page, required Map<String, dynamic>? filters}) =>
                repo.getFiltrados(page: page, filtros: filters),
      );

  bool _isFiltering = false;
  bool get isFiltering => _isFiltering;

  String? _titulo;
  String? _hashtags;

  String? get titulo => _titulo;
  String? get hashtags => _hashtags;

  bool get shouldShowInitialLoading {
    final hasTitulo = _titulo != null && _titulo!.isNotEmpty;
    final hasHashtags = _hashtags != null && _hashtags!.isNotEmpty;
    return hasTitulo || hasHashtags;
  }

  void setTitulo(String? value) {
    if (value == _titulo) return;
    _titulo = value;
    if (value != null && value.trim().isNotEmpty) {
      _startFiltering();
    }
  }

  void setHashtags(String? value) {
    if (value == _hashtags) return;
    _hashtags = value;
    if (value != null && value.trim().isNotEmpty) {
      _startFiltering();
    }
  }

  Timer? _debounce;

  void _startFiltering() {
    _isFiltering = true;
    notifyListeners();

    _debounce?.cancel();
    _applyFilters();
  }

  Future<void> _applyFilters() async {
    final filters = <String, dynamic>{};

    if (_titulo != null && _titulo!.isNotEmpty) {
      filters['titulo'] = _titulo;
    }

    if (_hashtags != null && _hashtags!.isNotEmpty) {
      filters['hashtags'] = _hashtags;
    }

    await applyFilters(filters);

    _isFiltering = false;
    notifyListeners();
  }

  void resetExplorar({bool notify = true}) {
    _titulo = null;
    _hashtags = null;
    _isFiltering = false;
    _debounce?.cancel();

    resetPagination();
    if (notify) notifyListeners();
  }

  @override
  void dispose() {
    _debounce?.cancel();
    super.dispose();
  }
}
