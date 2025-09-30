import 'package:sam_app/data/models/curso_model.dart';
import 'package:sam_app/data/services/curso_service.dart';

class CursoRepository {
  final CursoService service = CursoService();

  Future<List<CursoModel>> getCursosPorInstituicao({
    required int idInstituicao,
  }) {
    return service.getCursosPorInstituicao(idInstituicao: idInstituicao);
  }
}
