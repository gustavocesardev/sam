import 'package:flutter/material.dart';
import 'package:sam_app/data/models/grupo_estudo_model.dart';
import 'package:sam_app/data/models/membro_model.dart';
import 'package:sam_app/data/repositories/grupo_estudo/grupos_estudo_repository.dart';
import 'package:sam_app/data/services/http_service.dart';

class GrupoEstudoViewModel extends ChangeNotifier {
  final int idGrupoEstudo;

  bool _isLoading = true;
  bool get isLoading => _isLoading;

  late GrupoEstudoModel grupo;

  List<MembroModel> membros = [];

  final GruposEstudoRepository repository = GruposEstudoRepository();
  final HttpService httpService = HttpService();

  GrupoEstudoViewModel({required this.idGrupoEstudo});

  Future<void> loadGrupo() async {
    try {

      _isLoading = true;
      notifyListeners();

      grupo = await repository.index(id: idGrupoEstudo);
      membros = await repository.getMembros(idGrupo: idGrupoEstudo);

    } catch (e) {
      debugPrint('Erro ao carregar grupo: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<int?> ingressarGrupo(int idUsuario) async {
    try {

      final MembroModel novoMembro = await repository.ingressarMembro(
        idUsuario: idUsuario,
        idGrupoEstudo: idGrupoEstudo,
      );

      membros.add(novoMembro);
      notifyListeners();

      return novoMembro.idMembro;

    } catch (e) {
      debugPrint('Erro ao ingressar no grupo: $e');
      return null;
    }
  }

  Future<bool> sairDoGrupo({
    required int idUsuario,
    required int idMembro,
  }) async {
    try {

      await repository.removerMembro(
        idMembro: idMembro,
        idUsuario: idUsuario,
        idGrupoEstudo: idGrupoEstudo,
      );

      membros.removeWhere((m) => m.idMembro == idMembro);

      notifyListeners();
      return true;

    } catch (e) {
      debugPrint('Erro ao sair do grupo: $e');
      return false;
    }
  }
}
