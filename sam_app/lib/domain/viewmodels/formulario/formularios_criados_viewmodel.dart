import 'package:sam_app/data/repositories/formulario/formulario_repository.dart';
import 'package:sam_app/domain/viewmodels/formulario/formularios_viewmodel.dart';

class FormulariosCriadosViewmodel extends FormulariosViewmodel {
  FormulariosCriadosViewmodel(FormularioRepository repo)
    : super(
        fetchForms: ({required int page, Map<String, dynamic>? filters}) =>
            repo.getCriados(page: page),
      );
}
