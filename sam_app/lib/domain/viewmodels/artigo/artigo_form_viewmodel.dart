import 'dart:io';

import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:flutter_quill/flutter_quill.dart' as quill;

class ArtigoFormViewModel extends ChangeNotifier {
  final int? idArtigo;

  ArtigoFormViewModel({this.idArtigo});

  final TextEditingController tituloController = TextEditingController();
  final TextEditingController hashtagsController = TextEditingController();
  final quill.QuillController conteudoController = quill.QuillController.basic();

  File? pdfSelecionado;
  bool isLoading = false;

  Future<void> init() async {
    if (idArtigo != null) {
      await carregarArtigo(idArtigo!);
    }
  }

  Future<void> carregarArtigo(int id) async {
    isLoading = true;
    notifyListeners();

    // TODO: Buscar artigo do serviço/repositório
    // Preencher os controllers e arquivo selecionado
    // Exemplo:
    // final artigo = await ArtigoService.getById(id);
    // tituloController.text = artigo.titulo;
    // hashtagsController.text = artigo.hashtags;
    // conteudoController.document = ... (carregar documento do artigo)
    // pdfSelecionado = ...;

    isLoading = false;
    notifyListeners();
  }

  Future<void> selecionarPDF() async {
    final result = await FilePicker.platform.pickFiles(
      type: FileType.custom,
      allowedExtensions: ['pdf'],
    );
    if (result != null && result.files.single.path != null) {
      pdfSelecionado = File(result.files.single.path!);
      notifyListeners();
    }
  }

  void salvarArtigo() {
    // TODO: integrar com serviço/repositório para salvar ou atualizar
    if (idArtigo == null) {
      // Salvar artigo
    } else {
      // Editar artigo
    }
  }

  @override
  void dispose() {
    tituloController.dispose();
    hashtagsController.dispose();
    conteudoController.dispose();
    super.dispose();
  }
}