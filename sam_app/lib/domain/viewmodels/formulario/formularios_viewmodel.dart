import 'package:flutter/material.dart';
import 'package:sam_app/data/models/formulario_model.dart';

typedef FormFetcher =
    Future<List<FormularioModel>> Function({
      required int page,
      required Map<String, dynamic>? filters,
    });

class FormulariosViewmodel extends ChangeNotifier {
  final FormFetcher fetchForms;

  FormulariosViewmodel({required this.fetchForms});

  List<FormularioModel> _forms = [];
  List<FormularioModel> get forms => _forms;

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
    _forms = [];
    _hasMore = true;

    _isLoadingInitial = true;
    notifyListeners();

    final newForms = await fetchForms(page: _page, filters: _filters);

    _forms = newForms;
    _hasMore = newForms.isNotEmpty;
    _page++;

    _isLoadingInitial = false;
    notifyListeners();
  }

  Future<void> loadMore() async {
    if (_isLoadingMore || !_hasMore) return;

    _isLoadingMore = true;
    notifyListeners();

    final newForms = await fetchForms(page: _page, filters: _filters);

    _forms.addAll(newForms);
    _hasMore = newForms.isNotEmpty;
    _page++;

    _isLoadingMore = false;
    notifyListeners();
  }

  bool get hasMore => _hasMore;

  bool get isLoading => _isLoadingInitial || _isLoadingMore;
  
  void resetPagination() {
    _forms = [];
    _filters = {};
    _page = 1;
    _hasMore = true;
  }

}
