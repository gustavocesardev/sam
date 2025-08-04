import 'dart:async';

import 'package:sam_app/data/repositories/formulario/formulario_repository.dart';
import 'package:sam_app/domain/viewmodels/formulario/formularios_viewmodel.dart';

class FormulariosExplorarViewmodel extends FormulariosViewmodel {
  FormulariosExplorarViewmodel(FormularioRepository repo)
    : super(
        fetchForms:
            ({required int page, required Map<String, dynamic>? filters}) =>
                repo.getFiltrados(page: page, filtros: filters),
      );

  bool _isFiltering = false;
  bool get isFiltering => _isFiltering;

  bool _showAdvancedFilters = false;
  bool get showAdvancedFilters => _showAdvancedFilters;

  String? _selectedTipo;
  int? _selectedIdCurso;

  String? get selectedTipo => _selectedTipo;
  int? get selectedIdCurso => _selectedIdCurso;

  bool get shouldShowInitialLoading {
    final hasTitulo = _titulo != null && _titulo!.isNotEmpty;
    final hasTipo = _selectedTipo != null && _selectedTipo != 'Todos';
    final hasCurso = _selectedIdCurso != null && _selectedIdCurso != 0;
    return hasTitulo || hasTipo || hasCurso;
  }

  void setSelectedTipo(String? tipo) {
    _selectedTipo = tipo;
    _isFiltering = true;
    notifyListeners();
    _debounceFilters();
  }

  void setSelectedCurso(int? curso) {
    _selectedIdCurso = curso;
    _isFiltering = true;
    notifyListeners();
    _debounceFilters();
  }

  void toggleAdvancedFilters() {
    _showAdvancedFilters = !_showAdvancedFilters;
    notifyListeners();
  }

  Timer? _debounce;

  void onTituloChanged(String text) {
    if (text.isEmpty) return;
    _isFiltering = true;
    notifyListeners();
    _debounceFilters();
  }

  void _debounceFilters() {
    _debounce?.cancel();
    _debounce = Timer(const Duration(seconds: 2), _applyFilters);
  }

  Future<void> _applyFilters() async {
    final filters = <String, dynamic>{};

    if (_selectedTipo != null && _selectedTipo != 'Todos') {
      filters['tipo'] = _selectedTipo;
    }

    if (_selectedIdCurso != null && _selectedIdCurso != 0) {
      filters['id_curso'] = _selectedIdCurso;
    }

    if (_titulo != null && _titulo!.isNotEmpty) {
      filters['titulo'] = _titulo;
    }

    await applyFilters(filters);

    _isFiltering = false;
    notifyListeners();
  }

  String? _titulo;
  void setTitulo(String value) {
    _titulo = value;
    onTituloChanged(value);
  }

  void resetExplorar({bool notify = true}) {
    _selectedTipo = null;
    _selectedIdCurso = null;
    _titulo = null;
    _showAdvancedFilters = false;
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
