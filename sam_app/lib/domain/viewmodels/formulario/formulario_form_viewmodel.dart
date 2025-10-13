import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:sam_app/data/enums/tipo_formulario_enum.dart';
import 'package:sam_app/data/repositories/formulario/formulario_repository.dart';
import 'package:sam_app/data/requests/formulario_request.dart';

class FormularioFormViewModel extends ChangeNotifier {
  final int idUsuario;
  final int? idFormulario;

  FormularioFormViewModel({this.idFormulario, required this.idUsuario});

  final TextEditingController tituloController = TextEditingController();
  final TextEditingController descricaoController = TextEditingController();
  final TextEditingController googleFormsController = TextEditingController();
  final TextEditingController dataLimiteController = TextEditingController();

  String? tipoSelecionado;

  bool isLoading = false;
  bool isLoadingData = false;
  bool isLoadingExclude = false;

  void setLoading(bool value) {
    isLoading = value;
    notifyListeners();
  }

  void setLoadingData(bool value) {
    isLoadingData = value;
    notifyListeners();
  }

  void setLoadingExclude(bool value) {
    isLoadingExclude = value;
    notifyListeners();
  }

  Future<void> init() async {
    if (idFormulario != null) {
      await carregarFormulario(idFormulario!);
    }
  }

  Future<void> carregarFormulario(int id) async {
    setLoadingData(true);
    final repository = FormularioRepository();
    final formulario = await repository.index(id: id);

    tituloController.text = formulario.titulo;
    descricaoController.text = formulario.descricao;
    tipoSelecionado = formulario.tipo.codigo;
    googleFormsController.text = formulario.linkForms;

    final DateTime data = DateFormat(
      'yyyy-MM-dd',
    ).parse(formulario.dataLimite.toString());
    dataLimiteController.text = DateFormat('dd/MM/yyyy').format(data);

    setLoadingData(false);
    notifyListeners();
  }

  void setTipoSelecionado(String? value) {
    tipoSelecionado = value;
    notifyListeners();
  }

  Future<void> excluirFormulario() async {
    setLoadingExclude(true);
    final repository = FormularioRepository();
    await repository.delete(id: idFormulario!);
  }

  Future<void> salvarFormulario() async {
    setLoading(true);
    notifyListeners();

    final repository = FormularioRepository();

    try {

      final DateTime data = DateFormat(
        'dd/MM/yyyy',
      ).parse(dataLimiteController.text.trim());

      final dataFormatada = DateFormat('yyyy-MM-dd').format(data);

      final FormularioRequest formularioRequest = FormularioRequest(
        idFormulario: idFormulario,
        idUsuario: idUsuario,
        titulo: tituloController.text.trim(),
        descricao: descricaoController.text.trim(),
        tipo: tipoSelecionado ?? TipoFormularioEnum.ge.codigo,
        linkForms: googleFormsController.text.trim(),
        dataLimite: dataFormatada,
      );

      await repository.store(request: formularioRequest);

      tituloController.clear();
      descricaoController.clear();
      googleFormsController.clear();
      dataLimiteController.clear();
      tipoSelecionado = null;

      notifyListeners();
      
    } catch (e) {
      rethrow;
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
