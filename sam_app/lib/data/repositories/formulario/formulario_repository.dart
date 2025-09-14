import 'package:sam_app/data/models/formulario_model.dart';
import 'package:sam_app/data/requests/formulario_request.dart';
import 'package:sam_app/data/services/formulario/formulario_service.dart';

class FormularioRepository {
  final FormularioService service = FormularioService();

  FormularioRepository(FormularioService formularioService);

  Future<FormularioModel> index({required int id}) {
    return service.index(id: id);
  }

  Future<List<FormularioModel>> getFiltrados({
    int page = 1,
    Map<String, dynamic>? filtros,
  }) {
    return service.fetchFiltrados(page: page, filtros: filtros);
  }

  Future<List<FormularioModel>> getCriados({int page = 1}) {
    return service.fetchCriados(page: page);
  }

  Future<void> store({required FormularioRequest request}) async {
    await service.store(request: request);
  }

  Future<void> delete({required int id}) {
    return service.delete(id: id);
  }
}
