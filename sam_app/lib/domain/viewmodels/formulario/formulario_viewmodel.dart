import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:sam_app/data/models/formulario_model.dart';
import 'package:sam_app/data/repositories/formulario/formulario_repository.dart';

class FormularioViewmodel extends ChangeNotifier {
  final int idFormulario;

  FormularioViewmodel({required this.idFormulario});

  bool isLoading = false;
  FormularioModel? formulario;

  void setLoading(bool value) {
    isLoading = value;
    notifyListeners();
  }

  Future<void> loadFormulario() async {
    setLoading(true);
    try {
      final repository = FormularioRepository();
      final f = await repository.index(id: idFormulario);

      formulario = FormularioModel(
        id: f.id,
        idUsuario: f.idUsuario,
        nome: f.nome,
        periodo: f.periodo,
        curso: f.curso,
        titulo: f.titulo,
        descricao: f.descricao,
        tipo: f.tipo,
        situacao: f.situacao,
        linkForms: f.linkForms,
        dataLimite: DateFormat('yyyy-MM-dd').parse(f.dataLimite.toString()),
        criadoEm: f.criadoEm
      );
    } catch (e) {
      formulario = null;
    }
    setLoading(false);
  }
}
