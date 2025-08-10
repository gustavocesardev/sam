import 'package:flutter/material.dart';

class FormularioFormViewModel extends ChangeNotifier {
  final int? idFormulario;

  FormularioFormViewModel({this.idFormulario});

  final TextEditingController tituloController = TextEditingController();
  final TextEditingController descricaoController = TextEditingController();
  final TextEditingController googleFormsController = TextEditingController();

  String? tipoSelecionado;

  bool isLoading = false;

  Future<void> init() async {
    if (idFormulario != null) {
      await carregarFormulario(idFormulario!);
    }
  }

  Future<void> carregarFormulario(int id) async {
    isLoading = true;
    notifyListeners();

    // TODO: buscar dados do serviço/repositório
    // Exemplo:
    // final formulario = await FormularioService.getById(id);
    // tituloController.text = formulario.titulo;
    // descricaoController.text = formulario.descricao;
    // tipoSelecionado = formulario.tipo;
    // googleFormsController.text = formulario.googleFormsLink;

    isLoading = false;
    notifyListeners();
  }

  void setTipoSelecionado(String? value) {
    tipoSelecionado = value;
    notifyListeners();
  }

  void salvarFormulario() {
    // TODO: integrar service/repository para salvar ou atualizar
    if (idFormulario == null) {
      // Salvar formulário
    } else {
      // Editar formulário
    }
  }

  @override
  void dispose() {
    tituloController.dispose();
    descricaoController.dispose();
    googleFormsController.dispose();
    super.dispose();
  }
}