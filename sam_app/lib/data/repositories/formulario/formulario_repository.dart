import 'package:sam_app/data/models/formulario_model.dart';
import 'package:sam_app/data/services/formulario/formulario_service.dart';

class FormularioRepository {
  final FormularioService service = FormularioService();

  Future<List<FormularioModel>> getFiltrados({
    int page = 1,
    Map<String, dynamic>? filtros,
  }) {
    return service.fetchFiltrados(page: page, filtros: filtros);
  }

  Future<List<FormularioModel>> getCriados({int page = 1}) {
    return service.fetchCriados(page: page);
  }
}
